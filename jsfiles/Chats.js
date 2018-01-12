import AjaxCall from "./AjaxCall";
import HandleContent from "./HandleContent";
const handleContent = new HandleContent();
const ajaxCall = new AjaxCall();

const Chats = class {
	updateChat(){
		let lastPostTime = "";
		const chats = document.getElementsByClassName("chats");
		if(chats.length >= 1){
			let lengthOfPosts = chats.length;
			lengthOfPosts = lengthOfPosts - 1;
			const latestPost = chats[lengthOfPosts].textContent;
			const stringLength = latestPost.length;
			const startPos = stringLength - 19;		//19 is length of the datatime portion
			lastPostTime = latestPost.substring(startPos, stringLength);
		}
		const friendId = document.chat_form.friend_id.value;
		const msg = "get_recent_post&friend_id=" + friendId + "&last_post_time=" + lastPostTime;
		if(document.getElementsByName("chat_form")) {
			const chatForm = document.getElementsByName("chat_form");
			const url = chatForm[0].getAttribute("action");
			const fullUrl = `${url}?${msg}`;
			ajaxCall.getMethod (fullUrl, handleContent.chatMessage, false );
		}
	}

}
export default Chats;