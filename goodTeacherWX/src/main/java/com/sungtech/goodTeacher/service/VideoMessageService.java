package com.sungtech.goodTeacher.service;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.sungtech.goodTeacher.pojo.VideoMessage;

public class VideoMessageService {

	public VideoMessage getMessageData(String userMessage) {
		Document doc = null;
		VideoMessage message = null;
		try {
			doc = DocumentHelper.parseText(userMessage);
			Element rootElt = doc.getRootElement();

			message = new VideoMessage();
			message.setToUserName(rootElt.elementTextTrim("ToUserName"));
			message.setFromUserName(rootElt.elementTextTrim("FromUserName"));
			message.setCreateTime(rootElt.elementTextTrim("CreateTime"));
			message.setMsgType(rootElt.elementTextTrim("MsgType"));
			message.setMediaId(rootElt.elementTextTrim("MediaId"));
			message.setThumbMediaId(rootElt.elementTextTrim("ThumbMediaId"));
			message.setMsgId(rootElt.elementTextTrim("MsgId"));
			
		} catch (Exception e) {
			e.printStackTrace();
		}
		return message;
	}

}
