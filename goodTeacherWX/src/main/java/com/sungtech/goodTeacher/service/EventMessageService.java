package com.sungtech.goodTeacher.service;

import org.dom4j.Element;

import com.sungtech.goodTeacher.pojo.Evt4ClickMessage;
import com.sungtech.goodTeacher.pojo.Evt4LocationMessage;
import com.sungtech.goodTeacher.pojo.Message;

public class EventMessageService {
	private EventMessageService() {
	}

	public static Message getBaseEvtMessage(Element rootElement) {
		Message message = new Message();
		setBaseEvtMessage(message, rootElement);
		return message;
	}

	static void setBaseEvtMessage(Message message, Element rootElement) {
		message.setToUserName(rootElement.elementTextTrim("ToUserName"));
		message.setFromUserName(rootElement.elementTextTrim("FromUserName"));
		message.setCreateTime(rootElement.elementTextTrim("CreateTime"));
		message.setMsgType(rootElement.elementTextTrim("MsgType"));
		message.setEvent(rootElement.elementTextTrim("Event"));
	}

	public static Evt4LocationMessage getLoactionEvtMessage(Element rootElement) {
		Evt4LocationMessage message = new Evt4LocationMessage();
		setBaseEvtMessage(message, rootElement);
		message.setLatitude(rootElement.elementTextTrim("Latitude"));
		message.setLongitude(rootElement.elementTextTrim("Longitude"));
		message.setPrecision(rootElement.elementTextTrim("Precision"));
		return message;
	}

	public static Evt4ClickMessage getClickEvtMessage(Element rootElement) {
		Evt4ClickMessage message = new Evt4ClickMessage();
		setBaseEvtMessage(message, rootElement);
		message.setEventKey(rootElement.elementTextTrim("EventKey"));
		return message;
	}

}
