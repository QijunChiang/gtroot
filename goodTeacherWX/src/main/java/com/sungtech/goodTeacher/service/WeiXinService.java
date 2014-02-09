package com.sungtech.goodTeacher.service;

import java.util.Date;
import java.util.Iterator;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpSession;

import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.sungtech.goodTeacher.pojo.ImageMessage;
import com.sungtech.goodTeacher.pojo.LinkMessage;
import com.sungtech.goodTeacher.pojo.LocationMessage;
import com.sungtech.goodTeacher.pojo.Message;
import com.sungtech.goodTeacher.pojo.PopularCategory;
import com.sungtech.goodTeacher.pojo.SecondCategory;
import com.sungtech.goodTeacher.pojo.TopCategory;
import com.sungtech.goodTeacher.pojo.Message.MessageType;
import com.sungtech.goodTeacher.pojo.TextMessage;
import com.sungtech.goodTeacher.pojo.VideoMessage;
import com.sungtech.goodTeacher.pojo.VoiceMessage;
import com.sungtech.goodTeacher.util.Util;

public class WeiXinService {
	private static Map<String, PopularCategory> hotMap = new LinkedHashMap<String, PopularCategory>();
	
	static{
		PopularCategory pc1 = new PopularCategory();
		pc1.setCategoryIndex("22");
		pc1.setChooseIndex("1");
		pc1.setCategoryName("钢琴");
		pc1.setCategoryPicUrl("http://223.202.120.149/goodTeacherWX/image/gangqin.jpg");
		
		PopularCategory pc2 = new PopularCategory();
		pc2.setCategoryIndex("23");
		pc2.setChooseIndex("2");
		pc2.setCategoryName("弦乐");
		pc2.setCategoryPicUrl("http://223.202.120.149/goodTeacherWX/image/shuxue.jpg");
		
		PopularCategory pc3 = new PopularCategory();
		pc3.setCategoryIndex("25");
		pc3.setChooseIndex("3");
		pc3.setCategoryName("管乐");
		pc3.setCategoryPicUrl("http://223.202.120.149/goodTeacherWX/image/shuxue.jpg");
		
		PopularCategory pc4 = new PopularCategory();
		pc4.setCategoryIndex("61");
		pc4.setChooseIndex("4");
		pc4.setCategoryName("器械健身");
		pc4.setCategoryPicUrl("http://223.202.120.149/goodTeacherWX/image/jianshen.jpg");
		
		PopularCategory pc5 = new PopularCategory();
		pc5.setCategoryIndex("67");
		pc5.setChooseIndex("5");
		pc5.setCategoryName("瑜伽");
		pc5.setCategoryPicUrl("http://223.202.120.149/goodTeacherWX/image/jianshen.jpg");
		
		PopularCategory pc6 = new PopularCategory();
		pc6.setCategoryIndex("53");
		pc6.setChooseIndex("6");
		pc6.setCategoryName("厨师");
		pc6.setCategoryPicUrl("http://223.202.120.149/goodTeacherWX/image/chushi.jpg");
		
		hotMap.put(pc1.getChooseIndex(), pc1);
		hotMap.put(pc2.getChooseIndex(), pc2);
		hotMap.put(pc3.getChooseIndex(), pc3);
		hotMap.put(pc4.getChooseIndex(), pc4);
		hotMap.put(pc5.getChooseIndex(), pc5);
		hotMap.put(pc6.getChooseIndex(), pc6);
	}

	public String UserMessageProcess(String userMessage, HttpSession session){
		Document doc = null;
		String xmlText = null;
		try {
			doc = DocumentHelper.parseText(userMessage);
			Element rootElt = doc.getRootElement();
			String msgType = rootElt.elementTextTrim("MsgType");
			
			if(msgType.equalsIgnoreCase(MessageType.Event.getValue())){
				EventMessageService message = new EventMessageService();
				Message msg = message.getMessageData(userMessage);
				if("subscribe".equalsIgnoreCase(msg.getEvent())){
					doc = DocumentHelper.createDocument();
					doc.setXMLEncoding("utf-8");
					Element xmlElement = doc.addElement("xml");
			        
					Element toUserNameElement = xmlElement.addElement("ToUserName");
			        toUserNameElement.setText(msg.getFromUserName());
			        
			        Element fromUserNameElement = xmlElement.addElement("FromUserName");
			        fromUserNameElement.setText(msg.getToUserName());
			        
			        Element createTimeElement = xmlElement.addElement("CreateTime");
			        createTimeElement.setText(String.valueOf(new Date().getTime()));
			        
			        Element msgTypeElement = xmlElement.addElement("MsgType");
			        msgTypeElement.setText("text");
			        
			        String reply = "欢迎您关注好老师微信平台\n想要找老师，请回复以下编号：\n";
			        Iterator<String> it = hotMap.keySet().iterator();
					while(it.hasNext()){
						String key = it.next();
						PopularCategory pc = hotMap.get(key);
						
						reply += pc.getChooseIndex() + ":" + pc.getCategoryName() + "\n";
					}
			        Element contentElement = xmlElement.addElement("Content");
			        contentElement.addCDATA(reply);
					
					xmlText = doc.asXML();
					System.out.println(xmlText);
				}
				
			}else if(msgType.equalsIgnoreCase(MessageType.Text.getValue())){
				TextMessageService message = new TextMessageService();
				TextMessage textMessage = message.getMessageData(userMessage);
				
				String reply = "";
				TopCategory topCategory = null;
				SecondCategory secondCategory = null;
				List<TopCategory> categoryList = (List<TopCategory>) session.getAttribute("categoryList");
				if(Util.isNumeric(textMessage.getContent())) {
					if(textMessage.getContent().length() == 1){
						Iterator<String> it = hotMap.keySet().iterator();
						while(it.hasNext()){
							String key = it.next();
							if(key.startsWith(textMessage.getContent())){
								PopularCategory value = hotMap.get(key);
								int topIndex = Integer.parseInt(value.getCategoryIndex().substring(0, 1));
								int secondIndex = Integer.parseInt(value.getCategoryIndex().substring(1, 2));
								String id = categoryList.get(topIndex-1).getChildList().get(secondIndex-1).getId();
								Util.putOpenId(textMessage.getFromUserName(), id);

								doc = DocumentHelper.createDocument();
								doc.setXMLEncoding("utf-8");
								Element xmlElement = doc.addElement("xml");
						        
								Element toUserNameElement = xmlElement.addElement("ToUserName");
						        toUserNameElement.setText(textMessage.getFromUserName());
						        
						        Element fromUserNameElement = xmlElement.addElement("FromUserName");
						        fromUserNameElement.setText(textMessage.getToUserName());
						        
						        Element createTimeElement = xmlElement.addElement("CreateTime");
						        createTimeElement.setText(String.valueOf(new Date().getTime()));
						        
						        Element msgTypeElement = xmlElement.addElement("MsgType");
						        msgTypeElement.setText("text");
						        
						        Element contentElement = xmlElement.addElement("Content");
						        contentElement.addCDATA("请发送你的地理位置信息\n(下方[+]号)");
						        
//						        Element msgIdElement = xmlElement.addElement("MsgId");
//						        msgIdElement.setText(textMessage.getMsgId());
								
								xmlText = doc.asXML();
								System.out.println(xmlText);
							}
						}
						
//						if(Integer.parseInt(textMessage.getContent()) <= categoryList.size()){
//							topCategory = categoryList.get(Integer.parseInt(textMessage.getContent())-1);
//							for(int i = 1; i <= topCategory.getChildList().size(); i++) {
//								secondCategory = topCategory.getChildList().get(i-1);
//								reply += textMessage.getContent()+i+":"+secondCategory.getName()+"\n";
//							}
//							
//							doc = DocumentHelper.createDocument();
//					        Element xmlElement = doc.addElement("xml");
//					        
//					        Element toUserNameElement = xmlElement.addElement("ToUserName");
//					        toUserNameElement.setText(textMessage.getFromUserName());
//					        
//					        Element fromUserNameElement = xmlElement.addElement("FromUserName");
//					        fromUserNameElement.setText(textMessage.getToUserName());
//					        
//					        Element createTimeElement = xmlElement.addElement("CreateTime");
//					        createTimeElement.setText(String.valueOf(new Date().getTime()));
//					        
//					        Element msgTypeElement = xmlElement.addElement("MsgType");
//					        msgTypeElement.setText("text");
//					        
//					        Element contentElement = xmlElement.addElement("Content");
//					        contentElement.addCDATA(reply);
//					        
//					        Element msgIdElement = xmlElement.addElement("MsgId");
//					        msgIdElement.setText(textMessage.getMsgId());
//							
//							xmlText = doc.asXML();
//						}else{
//							reply = "想要找老师，请回复编号：\n";
//							for(int i = 1; i <= categoryList.size(); i++) {
//								topCategory = categoryList.get(i-1);
//								
//								reply += i+":"+topCategory.getName()+"\n";
//							}
//							
//							doc = DocumentHelper.createDocument();
//							doc.setXMLEncoding("utf-8");
//					        Element xmlElement = doc.addElement("xml");
//					        
//					        Element toUserNameElement = xmlElement.addElement("ToUserName");
//					        toUserNameElement.setText(textMessage.getFromUserName());
//					        
//					        Element fromUserNameElement = xmlElement.addElement("FromUserName");
//					        fromUserNameElement.setText(textMessage.getToUserName());
//					        
//					        Element createTimeElement = xmlElement.addElement("CreateTime");
//					        createTimeElement.setText(String.valueOf(new Date().getTime()));
//					        
//					        Element msgTypeElement = xmlElement.addElement("MsgType");
//					        msgTypeElement.setText("text");
//					        
//					        Element contentElement = xmlElement.addElement("Content");
//					        contentElement.addCDATA(reply);
//					        
//					        Element msgIdElement = xmlElement.addElement("MsgId");
//					        msgIdElement.setText(textMessage.getMsgId());
//							
//							xmlText = doc.asXML();
//							
//						}
					}
//					else if(textMessage.getContent().length() == 2){
//						int topIndex = Integer.parseInt(textMessage.getContent().substring(0, 1));
//						int secondIndex = Integer.parseInt(textMessage.getContent().substring(1, 2));
//						if(topIndex <= categoryList.size()){
//							topCategory = categoryList.get(topIndex-1);
//							if(secondIndex <= categoryList.get(topIndex-1).getChildList().size()){
//								String id = categoryList.get(topIndex-1).getChildList().get(secondIndex-1).getId();
//								
//								Util.putOpenId(textMessage.getFromUserName(), id);
//
//								doc = DocumentHelper.createDocument();
//								doc.setXMLEncoding("utf-8");
//								Element xmlElement = doc.addElement("xml");
//						        
//								Element toUserNameElement = xmlElement.addElement("ToUserName");
//						        toUserNameElement.setText(textMessage.getFromUserName());
//						        
//						        Element fromUserNameElement = xmlElement.addElement("FromUserName");
//						        fromUserNameElement.setText(textMessage.getToUserName());
//						        
//						        Element createTimeElement = xmlElement.addElement("CreateTime");
//						        createTimeElement.setText(String.valueOf(new Date().getTime()));
//						        
//						        Element msgTypeElement = xmlElement.addElement("MsgType");
//						        msgTypeElement.setText("text");
//						        
//						        Element contentElement = xmlElement.addElement("Content");
//						        contentElement.addCDATA("请发送你的地理位置信息");
//						        
//						        Element msgIdElement = xmlElement.addElement("MsgId");
//						        msgIdElement.setText(textMessage.getMsgId());
//								
//								xmlText = doc.asXML();
//								System.out.println(xmlText);
//								
//							}
//						}
//					}
				}else{
					reply = "想要找老师，请回复编号：\n";
					Iterator<String> it = hotMap.keySet().iterator();
					while(it.hasNext()){
						String key = it.next();
						PopularCategory pc = hotMap.get(key);
						
						reply += pc.getChooseIndex() + ":" + pc.getCategoryName() + "\n";
					}
					
					doc = DocumentHelper.createDocument();
					doc.setXMLEncoding("utf-8");
			        Element xmlElement = doc.addElement("xml");
			        
			        Element toUserNameElement = xmlElement.addElement("ToUserName");
			        toUserNameElement.setText(textMessage.getFromUserName());
			        
			        Element fromUserNameElement = xmlElement.addElement("FromUserName");
			        fromUserNameElement.setText(textMessage.getToUserName());
			        
			        Element createTimeElement = xmlElement.addElement("CreateTime");
			        createTimeElement.setText(String.valueOf(new Date().getTime()));
			        
			        Element msgTypeElement = xmlElement.addElement("MsgType");
			        msgTypeElement.setText("text");
			        
			        Element contentElement = xmlElement.addElement("Content");
			        contentElement.addCDATA(reply);
			        
//			        Element msgIdElement = xmlElement.addElement("MsgId");
//			        msgIdElement.setText(textMessage.getMsgId());
					
					xmlText = doc.asXML();
					System.out.println(xmlText);
						
				}

			}else if(msgType.equalsIgnoreCase(MessageType.Image.getValue())){
				ImageMessageService message = new ImageMessageService();
				ImageMessage imageMessage = message.getMessageData(userMessage);
			}else if(msgType.equalsIgnoreCase(MessageType.Link.getValue())){
				LinkMessageService message = new LinkMessageService();
				LinkMessage linkMessage = message.getMessageData(userMessage);
			}else if(msgType.equalsIgnoreCase(MessageType.Video.getValue())){
				VideoMessageService message = new VideoMessageService();
				VideoMessage videoMessage = message.getMessageData(userMessage);
			}else if(msgType.equalsIgnoreCase(MessageType.Voice.getValue())){
				VoiceMessageService message = new VoiceMessageService();
				VoiceMessage voiceMessage = message.getMessageData(userMessage);
			}else if(msgType.equalsIgnoreCase(MessageType.Location.getValue())){
				LocationMessageService message = new LocationMessageService();
				System.out.println(userMessage);
				LocationMessage locationMessage = message.getMessageData(userMessage);
				
				String requestURL = "http://www.kaopuu.com/gtapi/app/get_user_list?format=json&order=0&roleId=3&count=10&page=1&categoryIds="+
						Util.openIdMap.get(locationMessage.getFromUserName())+"&locationX="+
						locationMessage.getLocation_X()+"&locationY="+locationMessage.getLocation_Y();
				System.out.println(requestURL);
				
				String teacherInfo = Util.httpUrlRequest(requestURL);
				Gson gson = new Gson();
				Map<String, Object> tempMap = gson.fromJson(teacherInfo, new TypeToken<Map<String, Object>>(){}.getType());
				Util.putResult(locationMessage.getFromUserName(), tempMap);
				
				Map<String, Object> categoryMap = (Map<String, Object>) tempMap.get("data");
				List<Map<String, String>> teacherList = (List<Map<String, String>>) categoryMap.get("userList");
				List<Map<String, String>> categoryList = (List<Map<String, String>>) categoryMap.get("category");
				Map<String, String> teacherMap = null;
				
				doc = DocumentHelper.createDocument();
				doc.setXMLEncoding("utf-8");
		        Element xmlElement = doc.addElement("xml");
		        
		        Element toUserNameElement = xmlElement.addElement("ToUserName");
		        toUserNameElement.setText(locationMessage.getFromUserName());
		        
		        Element fromUserNameElement = xmlElement.addElement("FromUserName");
		        fromUserNameElement.setText(locationMessage.getToUserName());
		        
		        Element createTimeElement = xmlElement.addElement("CreateTime");
		        createTimeElement.setText(String.valueOf(new Date().getTime()));
		        
		        Element msgTypeElement = xmlElement.addElement("MsgType");
		        msgTypeElement.setText("news");
		        
		        Element articleCountElement = xmlElement.addElement("ArticleCount");
		        articleCountElement.setText(String.valueOf(teacherList.size()));
		        
		        Element articlesElement = xmlElement.addElement("Articles");
		        Element itemElement = articlesElement.addElement("item");
		        
		        Element titleElement = itemElement.addElement("Title");
				titleElement.setText((String) categoryList.get(0).get("name"));
				
				String url = null;
				Iterator<String> it = hotMap.keySet().iterator();
				while(it.hasNext()){
					String key = it.next();
					PopularCategory pc = hotMap.get(key);
					if(((String) categoryList.get(0).get("name")).indexOf(pc.getCategoryName()) != -1){
						url = pc.getCategoryPicUrl();
					}
				}
				Element picUrlElement = itemElement.addElement("PicUrl");
				if(url != null){
					picUrlElement.setText(url);
				}
		        
		        for(int i = 0; i < teacherList.size(); i++){
					teacherMap = teacherList.get(i);
					itemElement = articlesElement.addElement("item");
					
					titleElement = itemElement.addElement("Title");
					titleElement.addCDATA(teacherMap.get("name"));
					
					Element descriptionElement = itemElement.addElement("Description");
					descriptionElement.addCDATA(teacherMap.get("mile")+"米");
					
					picUrlElement = itemElement.addElement("PicUrl");
					picUrlElement.addCDATA("http://www.kaopuu.com/gtapi/"+teacherMap.get("photo"));
					
					picUrlElement = itemElement.addElement("Url");
					picUrlElement.addCDATA("http://223.202.120.149/goodTeacherWX/teacherInfo?openId="+locationMessage.getFromUserName()+"&userId="+teacherMap.get("userId"));
					System.out.println("openId="+locationMessage.getFromUserName()+"&userId="+teacherMap.get("userId"));
					

		        }
				
				xmlText = doc.asXML();
				System.out.println(xmlText);
			}

		} catch (Exception e) {
			e.printStackTrace();
		}
		
		return xmlText;
	}
}
