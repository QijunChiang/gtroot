package com.sungtech.goodTeacher.service;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.sungtech.goodTeacher.pojo.LocationMessage;

public class LocationMessageService {

	public LocationMessage getMessageData(String userMessage) {
		Document doc = null;
		LocationMessage message = null;
		try {
			doc = DocumentHelper.parseText(userMessage);
			Element rootElt = doc.getRootElement();

			message = new LocationMessage();
			message.setToUserName(rootElt.elementTextTrim("ToUserName"));
			message.setFromUserName(rootElt.elementTextTrim("FromUserName"));
			message.setCreateTime(rootElt.elementTextTrim("CreateTime"));
			message.setMsgType(rootElt.elementTextTrim("MsgType"));
			message.setLocation_X(rootElt.elementTextTrim("Location_X"));
			message.setLocation_Y(rootElt.elementTextTrim("Location_Y"));
			message.setScale(rootElt.elementTextTrim("Scale"));
			message.setLabel(rootElt.elementTextTrim("Label"));
			message.setMsgId(rootElt.elementTextTrim("MsgId"));
			
		} catch (Exception e) {
			e.printStackTrace();
		}
		return message;
	}

}
