package com.sungtech.goodTeacher.service;

import java.util.Date;
import java.util.Iterator;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;

import javax.servlet.http.HttpSession;

import org.apache.commons.lang3.StringUtils;
import org.dom4j.Document;
import org.dom4j.DocumentHelper;
import org.dom4j.Element;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.sungtech.goodTeacher.pojo.Evt4ClickMessage;
import com.sungtech.goodTeacher.pojo.Evt4LocationMessage;
import com.sungtech.goodTeacher.pojo.ImageMessage;
import com.sungtech.goodTeacher.pojo.LinkMessage;
import com.sungtech.goodTeacher.pojo.LocationMessage;
import com.sungtech.goodTeacher.pojo.Message;
import com.sungtech.goodTeacher.pojo.PopularCategory;
import com.sungtech.goodTeacher.pojo.TextMessage;
import com.sungtech.goodTeacher.pojo.TopCategory;
import com.sungtech.goodTeacher.pojo.VideoMessage;
import com.sungtech.goodTeacher.pojo.VoiceMessage;
import com.sungtech.goodTeacher.pojo.Message.MessageType;
import com.sungtech.goodTeacher.util.Util;
import com.sungtech.goodTeacher.util.user.UserHolder;
import com.sungtech.goodTeacher.util.user.UserUtil;

public class WeiXinService {
	final static Logger log = LoggerFactory.getLogger(WeiXinService.class);
	private static Map<String, PopularCategory> hotMap = new LinkedHashMap<String, PopularCategory>();
	private static Map<String, String> hotPicMap = new ConcurrentHashMap<String, String>();
	private static String DEFAULT_REPLY = "亲，请用相机拍下要解答的英语题目，我们将尽快给您解答。";

	private static int pageSize = 7;

	static {
		PopularCategory pc1 = new PopularCategory();
		pc1.setCategoryIndex("22");
		pc1.setChooseIndex("1");
		pc1.setCategoryName("钢琴");
		pc1.setCategoryPicUrl(Util.WX_URL + "/image/gangqin.jpg");

		PopularCategory pc2 = new PopularCategory();
		pc2.setCategoryIndex("23");
		pc2.setChooseIndex("2");
		pc2.setCategoryName("弦乐");
		pc2.setCategoryPicUrl(Util.WX_URL + "/image/shuxue.jpg");

		PopularCategory pc3 = new PopularCategory();
		pc3.setCategoryIndex("25");
		pc3.setChooseIndex("3");
		pc3.setCategoryName("管乐");
		pc3.setCategoryPicUrl(Util.WX_URL + "/image/shuxue.jpg");

		PopularCategory pc4 = new PopularCategory();
		pc4.setCategoryIndex("61");
		pc4.setChooseIndex("4");
		pc4.setCategoryName("器械健身");
		pc4.setCategoryPicUrl(Util.WX_URL + "/image/jianshen.jpg");

		PopularCategory pc5 = new PopularCategory();
		pc5.setCategoryIndex("67");
		pc5.setChooseIndex("5");
		pc5.setCategoryName("瑜伽");
		pc5.setCategoryPicUrl(Util.WX_URL + "/image/jianshen.jpg");

		PopularCategory pc6 = new PopularCategory();
		pc6.setCategoryIndex("53");
		pc6.setChooseIndex("6");
		pc6.setCategoryName("厨师");
		pc6.setCategoryPicUrl(Util.WX_URL + "/image/chushi.jpg");

		hotMap.put(pc1.getChooseIndex(), pc1);
		hotMap.put(pc2.getChooseIndex(), pc2);
		hotMap.put(pc3.getChooseIndex(), pc3);
		hotMap.put(pc4.getChooseIndex(), pc4);
		hotMap.put(pc5.getChooseIndex(), pc5);
		hotMap.put(pc6.getChooseIndex(), pc6);

		// 外语
		// 雅思
		hotPicMap.put("51f13e5fbfacb", "yasi.jpg");
		// 托福
		hotPicMap.put("51f13e5a10536", "tuofu.gif");
		// 四六级
		hotPicMap.put("52fc89c68fdda", "siliuji.jpg");
		// SAT
		hotPicMap.put("52ccf13619e3c", "sat.jpg");
		// 商务英语
		hotPicMap.put("51f74f5d339b0", "bec.jpg");

		// 艺术
		// 钢琴
		hotPicMap.put("52233fa2a4adc", "gangqin1.jpg");
		// 弦乐
		hotPicMap.put("52233f7f0bc09", "xuanle.jpg");
		// 声乐
		hotPicMap.put("51f751e3ca7e9", "shengle.jpg");
		// 管乐
		hotPicMap.put("51f13df867a1e", "guanle.jpg");

		// 初高中
		// 理科
		hotPicMap.put("52fc879cf22c8", "like.jpg");
		// 文科
		hotPicMap.put("52fc878a01041", "wenke.jpg");
	}

	public static void setPageSize(int pageSize) {
		if (pageSize < 5)
			pageSize = 5;
		else if (pageSize > 10)
			pageSize = 10;
		WeiXinService.pageSize = pageSize;
	}

	private Element createResponseDom(Message msg, String type) {
		Document doc = DocumentHelper.createDocument();
		doc.setXMLEncoding("utf-8");
		Element xmlElement = doc.addElement("xml");

		Element toUserNameElement = xmlElement.addElement("ToUserName");
		toUserNameElement.setText(msg.getFromUserName());

		Element fromUserNameElement = xmlElement.addElement("FromUserName");
		fromUserNameElement.setText(msg.getToUserName());

		Element createTimeElement = xmlElement.addElement("CreateTime");
		createTimeElement.setText(String.valueOf(new Date().getTime()));

		Element msgTypeElement = xmlElement.addElement("MsgType");
		msgTypeElement.setText(type);
		return xmlElement;
	}

	private String createResponseMsg(Message msg, String content) {
		Element xmlElement = this.createResponseDom(msg, "text");
		Element contentElement = xmlElement.addElement("Content");
		contentElement.addCDATA(content);
		return xmlElement.getDocument().asXML();
	}

	@SuppressWarnings("unchecked")
	private String getCourseList(Message msg) {
		UserHolder holder = UserUtil.getUserHolder(msg.getFromUserName());
		String requestURL = "http://www.kaopuu.com/gtapi/app/get_user_list?format=json&order=0&roleId=3&count="
				+ WeiXinService.pageSize
				+ "&page=1&categoryIds="
				+ holder.getCourseId()
				+ "&locationX="
				+ holder.getLocationX()
				+ "&locationY=" + holder.getLocationY();
		if (log.isDebugEnabled())
			log.debug("- RequestURL : {}", requestURL);
		String teacherInfo = Util.httpUrlRequest(requestURL);
		Gson gson = new Gson();
		Map<String, Object> tempMap = gson.fromJson(teacherInfo,
				new TypeToken<Map<String, Object>>() {
				}.getType());
		// Util.putResult(msg.getFromUserName(), tempMap);
		Map<String, Object> categoryMap = (Map<String, Object>) tempMap
				.get("data");
		List<Map<String, String>> teacherList = (List<Map<String, String>>) categoryMap
				.get("userList");
		List<Map<String, String>> categoryList = (List<Map<String, String>>) categoryMap
				.get("category");
		Map<String, String> teacherMap = null;

		// 创建news的响应dom
		Element xmlElement = this.createResponseDom(msg, "news");

		int len = teacherList.size();
		if (len < 10)
			len++;
		Element articleCountElement = xmlElement.addElement("ArticleCount");
		articleCountElement.setText(String.valueOf(len));

		Element articlesElement = xmlElement.addElement("Articles");
		Element itemElement = articlesElement.addElement("item");

		Element titleElement = itemElement.addElement("Title");
		if (categoryList == null || categoryList.isEmpty())
			titleElement.setText("暂无名称");
		else
			titleElement.setText((String) categoryList.get(0).get("name"));

		String enterKey = holder.getChooseKey();
		String url = null;
		if (StringUtils.length(enterKey) > 1) {
			url = hotPicMap.get(holder.getCourseId());
			if (StringUtils.isEmpty(url))
				url = "shuxue.jpg";
			url = Util.WX_URL + "/image/" + url;
		} else {
			Iterator<String> it = hotMap.keySet().iterator();
			while (it.hasNext()) {
				String key = it.next();
				PopularCategory pc = hotMap.get(key);
				if (((String) categoryList.get(0).get("name")).indexOf(pc
						.getCategoryName()) != -1) {
					url = pc.getCategoryPicUrl();
				}
			}
		}
		Element picUrlElement = itemElement.addElement("PicUrl");
		if (url != null) {
			picUrlElement.setText(url);
		}
		String nurl;
		for (int i = 0; i < teacherList.size(); i++) {
			teacherMap = teacherList.get(i);
			itemElement = articlesElement.addElement("item");

			titleElement = itemElement.addElement("Title");
			// titleElement.addCDATA(teacherMap.get("name"));
			// 2014-2-12 YZC 修改，
			String title = teacherMap.get("skill");
			String name = teacherMap.get("name");
			if (StringUtils.isEmpty(title))
				title = "[暂无]";
			if (StringUtils.isEmpty(name))
				name = "暂无名";
			titleElement.addCDATA(title);

			Element descriptionElement = itemElement.addElement("Description");
			descriptionElement.addCDATA(teacherMap.get("mile") + "米\n" + name);

			picUrlElement = itemElement.addElement("PicUrl");
			picUrlElement.addCDATA("http://www.kaopuu.com/gtapi/"
					+ teacherMap.get("photo"));

			picUrlElement = itemElement.addElement("Url");
			nurl = Util.WX_URL + "/teacherInfo?openId=" + msg.getFromUserName()
					+ "&userId=" + teacherMap.get("userId");
			picUrlElement.addCDATA(nurl);
		}
		return xmlElement.getDocument().asXML();
	}

	String getNormalNoticeMsg() {
		StringBuilder reply = new StringBuilder("想要找老师，请回复编号：\n");
		Iterator<String> it = hotMap.keySet().iterator();
		while (it.hasNext()) {
			String key = it.next();
			PopularCategory pc = hotMap.get(key);
			reply.append(pc.getChooseIndex()).append(":")
					.append(pc.getCategoryName()).append("\n");
		}
		return reply.toString();
	}

	/**
	 * @see 创建主课程列表信息
	 * @param msg
	 * @return
	 */
	private String createNormalReply(Message msg) {
		// this.getNormalNoticeMsg()
		return this.createResponseMsg(msg, DEFAULT_REPLY);
	}

	String subscribe(Message msg) {
		StringBuilder reply = new StringBuilder(
				"欢迎您关注好老师微信平台\n想要找老师，请回复以下编号：\n");
		Iterator<String> it = hotMap.keySet().iterator();
		while (it.hasNext()) {
			String key = it.next();
			PopularCategory pc = hotMap.get(key);
			reply.append(pc.getChooseIndex()).append(":")
					.append(pc.getCategoryName()).append("\n");
		}
		return this.createResponseMsg(msg, reply.toString());
	}

	private boolean setChooseCourse(String fromUserName, String ek,
			List<TopCategory> categoryList) {
		// ek 选择课程 长度为1，并且不是当前选择的课程，则要重新设置课程id
		if (ek.length() == 1 && !UserUtil.isCurrentChoose(fromUserName, ek)) {
			PopularCategory value = hotMap.get(ek);
			if (value == null)
				return false;
			int topIndex = Integer.parseInt(value.getCategoryIndex().substring(
					0, 1));
			int secondIndex = Integer.parseInt(value.getCategoryIndex()
					.substring(1, 2));
			// if(categoryList.size()<topIndex) 分类有问题
			String id = categoryList.get(topIndex - 1).getChildList()
					.get(secondIndex - 1).getId();
			UserUtil.putOpenId(fromUserName, id, ek);
		}
		return true;
	}

	private boolean setMenuClickCourse(String fromUserName, String ek) {
		// ek 选择课程 长度为1，并且不是当前选择的课程，则要重新设置课程id
		if (!UserUtil.isCurrentChoose(fromUserName, ek)) {
			UserUtil.putOpenId(fromUserName, ek, ek);
		}
		return true;
	}

	private String menuClick(Evt4ClickMessage msg) {
		String ek = StringUtils.trim(msg.getEventKey());
		boolean fg = this.setMenuClickCourse(msg.getFromUserName(), ek);
		if (!fg)
			return this.createNormalReply(msg);
		// 该用户无位置信息，提示选择位置信息
		if (!UserUtil.hasLocation(msg.getFromUserName()))
			return this
					.createResponseMsg(msg, "请发送你的地理位置信息(先左下方[键盘],在右下方[+]号)");
		return this.getCourseList(msg);
		// return this.createResponseMsg(msg, "输入非课程选择序号(1,2,3..)");
	}

	public String UserMessageProcess(String userMessage, HttpSession session) {
		Document doc = null;
		String xmlText = null;
		try {
			doc = DocumentHelper.parseText(userMessage);
			Element rootElement = doc.getRootElement();
			String msgType = rootElement.elementTextTrim("MsgType");
			String fromUser = rootElement.elementTextTrim("FromUserName");

			if (msgType.equalsIgnoreCase(MessageType.Event.getValue())) {
				String evt = rootElement.elementTextTrim("Event");
				if ("subscribe".equalsIgnoreCase(evt)) {
					Message gmsg = EventMessageService
							.getBaseEvtMessage(rootElement);
					xmlText = this.createResponseMsg(gmsg, DEFAULT_REPLY);
					// this.subscribe(gmsg);
					UserUtil.createUserHolder(gmsg.getFromUserName());
					return xmlText;
				} else if ("click".equalsIgnoreCase(evt)) {
					xmlText = this.menuClick(EventMessageService
							.getClickEvtMessage(rootElement));
				} else if ("location".equalsIgnoreCase(evt)) {
					Evt4LocationMessage locationMsg = EventMessageService
							.getLoactionEvtMessage(rootElement);
					if (!UserUtil.hasUserHolder(locationMsg.getFromUserName()))
						xmlText = this.createNormalReply(locationMsg);
					UserUtil.putLocation(locationMsg.getFromUserName(),
							locationMsg.getLatitude(),
							locationMsg.getLongitude());
					// return xmlText;
				} else if ("unsubscribe".equalsIgnoreCase(evt)) {
					if (log.isDebugEnabled())
						log.debug("- UnSubscribed user : {}", fromUser);
					UserUtil.removeUserHolder(fromUser);
					return xmlText;
				}
			} else if (msgType.equalsIgnoreCase(MessageType.Text.getValue())) {
				// xmlText=this.getContentReply(userMessage,session);
			} else if (msgType.equalsIgnoreCase(MessageType.Image.getValue())) {
				ImageMessageService message = new ImageMessageService();
				ImageMessage imageMessage = message.getMessageData(userMessage);
			} else if (msgType.equalsIgnoreCase(MessageType.Link.getValue())) {
				LinkMessageService message = new LinkMessageService();
				LinkMessage linkMessage = message.getMessageData(userMessage);
			} else if (msgType.equalsIgnoreCase(MessageType.Video.getValue())) {
				VideoMessageService message = new VideoMessageService();
				VideoMessage videoMessage = message.getMessageData(userMessage);
			} else if (msgType.equalsIgnoreCase(MessageType.Voice.getValue())) {
				VoiceMessageService message = new VoiceMessageService();
				VoiceMessage voiceMessage = message.getMessageData(userMessage);
			} else if (msgType
					.equalsIgnoreCase(MessageType.Location.getValue())) {
				LocationMessageService message = new LocationMessageService();
				System.out.println(userMessage);
				LocationMessage locationMessage = message
						.getMessageData(userMessage);
				UserUtil.putLocation(locationMessage.getFromUserName(),
						locationMessage.getLocation_X(),
						locationMessage.getLocation_Y());
				return this.getCourseList(locationMessage);
			}
			// 从新开始计算用户信息失效时间，默认10分钟
			UserUtil.userKick(fromUser);
		} catch (Exception e) {
			e.printStackTrace();
		}
		return xmlText;
	}

	String getContentReply(String userMessage, HttpSession session) {
		String xmlText = null;
		TextMessageService message = new TextMessageService();
		TextMessage textMessage = message.getMessageData(userMessage);
		List<TopCategory> categoryList = (List<TopCategory>) session
				.getAttribute("categoryList");
		if (Util.isNumeric(textMessage.getContent())) {
			boolean fg = this.setChooseCourse(textMessage.getFromUserName(),
					textMessage.getContent(), categoryList);
			if (!fg)
				return this.createNormalReply(textMessage);
			// return this.createResponseMsg(textMessage, "输入课程序号不存在!"
			// + this.getNormalNoticeMsg());
			if (UserUtil.hasLocation(textMessage.getFromUserName()))
				xmlText = this.getCourseList(textMessage);
			else
				xmlText = this.createResponseMsg(textMessage,
						"请发送你的地理位置信息\n(下方[+]号)");
		} else
			xmlText = this.createNormalReply(textMessage);
		return xmlText;
	}
}
