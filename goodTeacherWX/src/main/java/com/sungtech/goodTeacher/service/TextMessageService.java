package com.sungtech.goodTeacher.service;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.sungtech.goodTeacher.pojo.TextMessage;

public class TextMessageService {

	public TextMessage getMessageData(String userMessage) {
		Document doc = null;
		TextMessage message = null;
		try {
			doc = DocumentHelper.parseText(userMessage);
			Element rootElt = doc.getRootElement();

			message = new TextMessage();
			message.setToUserName(rootElt.elementTextTrim("ToUserName"));
			message.setFromUserName(rootElt.elementTextTrim("FromUserName"));
			message.setCreateTime(rootElt.elementTextTrim("CreateTime"));
			message.setMsgType(rootElt.elementTextTrim("MsgType"));
			message.setContent(rootElt.elementTextTrim("Content"));
			message.setMsgId(rootElt.elementTextTrim("MsgId"));
			
		} catch (Exception e) {
			e.printStackTrace();
		}
		
		return message;
	}
	
	

}
