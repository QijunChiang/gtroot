package com.sungtech.goodTeacher.service;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.sungtech.goodTeacher.pojo.ImageMessage;

public class ImageMessageService {

	public ImageMessage getMessageData(String userMessage) {
		Document doc = null;
		ImageMessage message = null;
		try {
			doc = DocumentHelper.parseText(userMessage);
			Element rootElt = doc.getRootElement();

			message = new ImageMessage();
			message.setToUserName(rootElt.elementTextTrim("ToUserName"));
			message.setFromUserName(rootElt.elementTextTrim("FromUserName"));
			message.setCreateTime(rootElt.elementTextTrim("CreateTime"));
			message.setMsgType(rootElt.elementTextTrim("MsgType"));
			message.setPicUrl(rootElt.elementTextTrim("PicUrl"));
			message.setMediaId(rootElt.elementTextTrim("MediaId"));
			message.setMsgId(rootElt.elementTextTrim("MsgId"));
			
		} catch (Exception e) {
			e.printStackTrace();
		}
		return message;
	}

}
