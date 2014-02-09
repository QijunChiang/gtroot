package com.sungtech.goodTeacher.pojo;

public class Message {

	private String toUserName;
	
	private String fromUserName;
	
	private String createTime;
	
	private String msgType;
	
	private String msgId;
	
	private String event;
	
	public String getEvent() {
		return event;
	}

	public void setEvent(String event) {
		this.event = event;
	}

	public String getToUserName() {
		return toUserName;
	}

	public void setToUserName(String toUserName) {
		this.toUserName = toUserName;
	}

	public String getFromUserName() {
		return fromUserName;
	}

	public void setFromUserName(String fromUserName) {
		this.fromUserName = fromUserName;
	}

	public String getCreateTime() {
		return createTime;
	}

	public void setCreateTime(String createTime) {
		this.createTime = createTime;
	}

	public String getMsgType() {
		return msgType;
	}

	public void setMsgType(String msgType) {
		this.msgType = msgType;
	}

	public String getMsgId() {
		return msgId;
	}

	public void setMsgId(String msgId) {
		this.msgId = msgId;
	}

	public enum MessageType {
		Text("text"),
		Image("image"),
		Voice("voice"),
		Video("video"),
		Location("location"),
		Link("Link"),
		Event("Event");
		
		private final String value;

		MessageType(String value) {
	        this.value = value;
	    }
	    
	    public String getValue() {
	        return value;
	    }
	}

}
