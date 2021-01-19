<?php
namespace App\Http\Controllers;

use Validator;
use App\Properties;
use App\ChatMessage;
use App\ChatChannel;
use App\ChatParticipant;
use App\ChatContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Managing Chat Data
 *
 * @author HIA YONGKUY
 */
class ChatController extends Controller
{
    /**
     * Send Chat Message
     *
     * @param  Illuminate\Http\Request
     * @return Response {ChatChannel model}
     */
    public function init($property_id)
    {
        $property = Properties::find($property_id);
        $chat_channel = new ChatChannel();

        if (!$property) {
            return $this->getResponseData("0", "Data validation failed.", "Property is not exist");
        }

        if (Auth::id() ==  $property->users->id) {
            return $this->getResponseData("0", "Data validation failed.", "You can not chat to yourself.");
        }

        if (!ChatChannel::exist(Auth::id(), $property->id)) {
            $chat_channel = $this->create(Auth::id(), $property->id);
        } else {
            $chat_channel =  ChatChannel::where('user_id', Auth::id())
                                        ->where('property_id', $property->id)
                                        ->first();
            $chat_channel->restoreParticipants(false);
        }

        return $this->getResponseData('1', "Success", $chat_channel);
    }

    public function get(Request $request, $id)
    {
        if (!ChatChannel::isParticipantAllow(Auth::id(), $id)) {
            return $this->getResponseData("0", "Unauthorized User", 'You are not allow to chat in this channel.');
        }

        // Property Relationship : 'property', 'property.currency'
        $deleted_at = ChatMessage::withTrashed()
                                 ->where('chat_channel_id', $id)
                                 ->where("user_id", Auth::id())
                                 ->max('deleted_at');

        if (is_null($deleted_at)) {
            $chat_channel =  ChatChannel::where('id', $id)
                                        ->with(['messages', 'messages.user', 'partner'])
                                        ->first();
        } else {
            $chat_channel =  ChatChannel::where('id', $id)
                                        ->with(['messages' => function ($query) use ($deleted_at) {
                                            $query->where('created_at', '>', $deleted_at);
                                        } , 'messages.user', 'partner'])
                                        ->first();
        }


        try {
            $this->flagMessage($id, 'seen');
        } catch (Exception $e) {
            return $this->getResponseData('0', "Server side Error", $e->getMessage());
        }

        return $this->getResponseData('1', "Success", $chat_channel);
    }
    public function list()
    {
        //Get All Channel id in 1 array which Auth::user() invole;
        $channel_id_array =  ChatChannel::getChannelIdsByUserID(Auth::id());

        $chat_channels = ChatChannel::whereIn('id', $channel_id_array)
                                    ->with(['property' => function ($query) {
                                        $query->withTrashed();
                                    }, 'property.currency', 'partner'])
                                    ->get();

        return $this->getResponseData('1', "Success", $chat_channels);
    }

    public function saveMessageFromRequest(Request $request)
    {
        $chat_channel = new ChatChannel();
        $participants; //Collection of who involve in the chat channel

        $validator = Validator::make($request->all(), [
                        'chat_channel_id' => 'required|integer|exists:'.$chat_channel->getTable().','.$chat_channel->getKeyName(),
                        'content' => 'required|max:1000'
                    ]);

        if ($validator->fails()) {
            return $this->getResponseData("0", "Data validation failed.", $validator->errors()->first());
        }

        if (!ChatChannel::isParticipantAllow(Auth::id(), $request->input('chat_channel_id'))) {
            return  $this->getResponseData("0", "Unauthorized User", 'You are not allow to chat in this channel.');
        }

        //checking if the property is deleted by owner
        if (is_null(ChatChannel::where('id', $request->input('chat_channel_id'))->first()->property)) {
            return  $this->getResponseData("0", "Property Unavailable", 'The property owner might remove his/her listing from our system');
        }
        //end Check if user is allow to chat in the specify channel or not
        try {
            $meesage = $this->saveMessage($request->input('chat_channel_id'), Auth::id(), $request->input('content'));
        } catch (Exception $e) {
            return $this->getResponseData('0', "Server side Error", $e->getMessage());
        }
        return $this->getResponseData('1', "Success", $meesage);
    }

    public function UnreadCount()
    {
        return $this->getResponseData('1', "Success", [ "count" => $this->getUnreadCount(Auth::id())  ]);
    }

    public function deleteFromRequest($id)
    {
        $chat_channel = ChatChannel::find($id);

        if (!$chat_channel) {
            return $this->getResponseData("0", "Data validation failed.", "Channel is not exist");
        }

        if (!ChatChannel::isParticipantAllow(Auth::id(), $id)) {
            return  $this->getResponseData("0", "Unauthorized User", 'You are not allow to delete this Channel');
        }

        $chat_channel->deleteMessage();
        return $this->getResponseData('1', "Success", 'Chat History has been deleted successfully.');
    }

    protected function create($user_id, $property_id)
    {
        $property = Properties::find($property_id);
        $chat_channel   = ChatChannel::create([
                                'user_id' => Auth::id(),
                                'property_id' => $property->id
                              ]);

        $chat_channel->participants()->saveMany([
            new ChatParticipant(['user_id' => $user_id]),
            new ChatParticipant(["user_id" => $property->users->id])
        ]);

        return $chat_channel;
    }

    protected function saveMessage($channel_id, $user_id, $content)
    {
        $message = ChatMessage::create([
            'chat_channel_id' => $channel_id,
            'user_id' => $user_id,
            'content' => $content
        ]);

        //restore the softDelete() participants;
        ChatChannel::where('id', $channel_id)->first()->restoreParticipants();

        return $message->fresh();
    }

    /**
    * Mark the Message of the
    *
    * @param  Integer $user_id, $property_id, $channel_id
    *         (if $channel_id provided, $user_id and $property_id will be ignore)
    * @return Boolean : True if Exist, False if not
    */
    protected function flagMessage($chat_channel_id, $flag = 'seen')
    {
        $chat_channel = ChatChannel::where('id', $chat_channel_id)->firstOrFail();

        if (!is_string($flag)) {
            throw new \InvalidArgumentException('Message Flag can only accepts String. '.gettype($flag). ' is given.');
        }

        if (!in_array($flag, $chat_channel->getMessageFlag())) {
            throw new \Exception("Invalid Value! "."Allowed values '". implode("','", $chat_channel->getMessageFlag()));
        }

        $sent_messages_id_array = $chat_channel->messages->where('flag', 'sent')->pluck('id')->toArray();

        if (count($sent_messages_id_array) <= 0) {
            return null;
        }

        ChatMessage::whereIn('id', $sent_messages_id_array)
                        ->where('user_id', $chat_channel->partner->user_id)
                        ->update([ 'flag' => $flag ]);

        return ChatMessage::whereIn('id', $sent_messages_id_array)
                          ->where('user_id', $chat_channel->partner->user_id)->get();
    }

    protected function getUnreadCount(int $user_id)
    {
        return count(ChatChannel::getUnreadMessageChannelIdsByUserID($user_id));
    }
}
