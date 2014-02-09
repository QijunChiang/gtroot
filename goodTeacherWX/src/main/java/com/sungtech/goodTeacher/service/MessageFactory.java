//package com.sungtech.goodTeacher.service;
//
//import java.util.ArrayList;
//import java.util.List;
//
//import org.dom4j.Document;
//import org.dom4j.DocumentHelper;
//import org.dom4j.Element;
//
//import com.sungtech.goodTeacher.pojo.Message.MessageType;
//import com.sungtech.goodTeacher.pojo.TextMessage;
//
//public class MessageFactory {
//
//	public static IMessageService getInstance(String msgType){
//		IMessageService message = null;
//
//		if(msgType.equalsIgnoreCase(MessageType.Text.getValue())){
//			message = new TextMessageService();
//		}else if(msgType.equalsIgnoreCase(MessageType.Image.getValue())){
//			message = new ImageMessageService();
//		}else if(msgType.equalsIgnoreCase(MessageType.Link.getValue())){
//			message = new LinkMessageService();
//		}else if(msgType.equalsIgnoreCase(MessageType.Video.getValue())){
//			message = new VideoMessageService();
//		}else if(msgType.equalsIgnoreCase(MessageType.Voice.getValue())){
//			message = new VoiceMessageService();
//		}else if(msgType.equalsIgnoreCase(MessageType.Location.getValue())){
//			message = new LocationMessageService();
//		}
//		
//		return message;
//	}
//}
