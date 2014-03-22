package com.sungtech.goodTeacher.action;

import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Map;

import org.apache.commons.lang3.StringUtils;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.opensymphony.xwork2.ActionSupport;
import com.sungtech.goodTeacher.util.Util;
import com.sungtech.goodTeacher.util.user.UserHolder;
import com.sungtech.goodTeacher.util.user.UserUtil;

public class TeacherInfoAction extends ActionSupport {
	private static final long serialVersionUID = -2205212110976108725L;
	private static final char[] week = new char[] { '1', '2', '3', '4', '5',
			'6', '0' };
	private static final Gson gson = new Gson();
	private String openId;

	private String userId;

	private String photo;

	private String name;

	private String distance;

	private String skill;

	private String address;

	private String teacherLogo;

	private String video;

	private String college;

	private String description;

	private List<Map<String, String>> dataList;

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public String getCollege() {
		return college;
	}

	public void setCollege(String college) {
		this.college = college;
	}

	public String getVideo() {
		return video;
	}

	public void setVideo(String video) {
		this.video = video;
	}

	public List<Map<String, String>> getDataList() {
		return dataList;
	}

	public String getTeacherLogo() {
		return teacherLogo;
	}

	public void setTeacherLogo(String teacherLogo) {
		this.teacherLogo = teacherLogo;
	}

	public void setDataList(List<Map<String, String>> dataList) {
		this.dataList = dataList;
	}

	public String getUserId() {
		return userId;
	}

	public void setUserId(String userId) {
		this.userId = userId;
	}

	public String getOpenId() {
		return openId;
	}

	public void setOpenId(String openId) {
		this.openId = openId;
	}

	public String getPhoto() {
		return photo;
	}

	public void setPhoto(String photo) {
		this.photo = photo;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getDistance() {
		return distance;
	}

	public void setDistance(String distance) {
		this.distance = distance;
	}

	public String getSkill() {
		return skill;
	}

	public void setSkill(String skill) {
		this.skill = skill;
	}

	public String getAddress() {
		return address;
	}

	public void setAddress(String address) {
		this.address = address;
	}

	@Override
	public String execute() throws Exception {
		Map<String, Object> teacherMap = null, temp;
		temp = Util.resultMap.get(openId);
		if (temp != null) {
			Map<String, Object> categoryMap = (Map<String, Object>) temp
					.get("data");
			List<Map<String, Object>> teacherList = (List<Map<String, Object>>) categoryMap
					.get("userList");
			for (int i = 0; i < teacherList.size(); i++) {
				temp = teacherList.get(i);
				if (StringUtils.equals(temp.get("userId").toString(),
						userId.trim()))
					teacherMap = temp;
			}
		}
		if (teacherMap != null) {
			this.setTeacherInfo(teacherMap);
		} else
			this.loadTeacherInfo(this.userId);

		String url = "http://www.kaopuu.com/gtapi/app/get_teach_course_list?format=json&count=10&page=1&userId="
				+ userId;
		String teacherInfo = Util.httpUrlRequest(url);
		Map<String, Object> tempMap = gson.fromJson(teacherInfo,
				new TypeToken<Map<String, Object>>() {
				}.getType());
		List<Map<String, String>> glist = (List<Map<String, String>>) tempMap
				.get("data");
		if (glist != null && !glist.isEmpty()) {
			for (int i = 0; i < glist.size(); i++) {
				Map<String, String> map = glist.get(i);
				String dt = map.get("teachTime");
				map.put("teachTime", setTeachTime(dt));
				dt = map.get("unit");
				map.put("unit", setPriceUnit(dt));
			}
		}
		this.dataList = glist;
		if (StringUtils.isEmpty(this.college))
			this.college = "暂无填写";
		if (StringUtils.isEmpty(this.description))
			this.description = "暂无填写";
		// else
		// this.description = this.description.replace("\n", "<br/>");
		return SUCCESS;
	}

	void setTeacherInfo(Map<String, Object> teacherMap) {
		String tmp = (String) teacherMap.get("introduction_image");
		if (StringUtils.isBlank(tmp))
			photo = Util.WX_URL + "/image/default_pic.png";
		else
			photo = "http://www.kaopuu.com/gtapi/" + tmp;
		video = (String) teacherMap.get("introduction_video");
		name = (String) teacherMap.get("name");
		distance = Util.getInstance((String) teacherMap.get("mile"));
		skill = (String) teacherMap.get("skill");
		address = ((Map<String, String>) teacherMap.get("location"))
				.get("info");
		tmp = (String) teacherMap.get("photo");
		if (StringUtils.isBlank(tmp))
			teacherLogo = Util.WX_URL + "/image/default_logo.png";
		else
			teacherLogo = "http://www.kaopuu.com/gtapi/" + tmp;
	}

	void loadTeacherInfo(String userId) {
		String url = "http://www.kaopuu.com/gtapi/app/get_user_info?format=json&userId="
				+ userId;
		String teacherInfo = Util.httpUrlRequest(url);
		Map<String, Object> tempMap = gson.fromJson(teacherInfo,
				new TypeToken<Map<String, Object>>() {
				}.getType());
		Map<String, Object> infoMap = (Map<String, Object>) tempMap.get("data");
		this.skill = (String) infoMap.get("skill");
		this.name = (String) infoMap.get("name");
		String tmp = (String) infoMap.get("photo");
		if (StringUtils.isBlank(tmp))
			this.teacherLogo = Util.WX_URL + "/image/default_logo.png";
		else
			this.teacherLogo = "http://www.kaopuu.com/gtapi/" + tmp;

		Map<String, Object> introMap = (Map<String, Object>) infoMap
				.get("introduction");
		Map<String, String> locationMap = (Map<String, String>) infoMap
				.get("location");
		Map<String, Object> videoMap = (Map<String, Object>) introMap
				.get("video");

		tmp = (String) videoMap.get("image");
		if (StringUtils.isBlank(tmp))
			tmp = this.getImage(introMap);
		if (StringUtils.isBlank(tmp))
			this.photo = Util.WX_URL + "/image/default_pic.png";
		else
			this.photo = "http://www.kaopuu.com/gtapi/" + tmp;
		this.video = (String) videoMap.get("url");
		String x = locationMap.get("x"), y = locationMap.get("y");
		double xd = Double.parseDouble(x);
		double yd = Double.parseDouble(y);
		UserHolder uh = UserUtil.getUserHolder(this.openId);
		if (uh != null) {
			this.distance = Util.getInstance(Util.getDistance(yd, xd,
					uh.getLocationY(), uh.getLocationX()));
		}
		this.address = locationMap.get("info");
		this.college = (String) infoMap.get("college");
		this.description = (String) introMap.get("description");
	}

	String getImage(Map<String, Object> introMap) {
		List<Map<String, String>> glist = (List<Map<String, String>>) introMap
				.get("image");
		if (glist != null && glist.size() > 0) {
			Map<String, String> m = glist.get(0);
			return m.get("image");
		}
		return null;
	}

	static String setTeachTime(String t) {
		char w[] = week;
		boolean empty = StringUtils.isEmpty(t);
		StringBuilder sb = new StringBuilder();
		for (int i = 0; i < w.length; i++) {
			char ch = w[i];
			int idx = empty ? -1 : t.indexOf(ch);
			sb.append("<li");
			if (idx != -1)
				sb.append(" class=\"cur\"");
			sb.append("><span>").append(getWeekDay(ch)).append("</span></li>");
		}
		return sb.toString();
	}

	static String setPriceUnit(String t) {
		if ("0".equals(t))
			return "小时";
		else if ("1".equals(t))
			return "课";
		else
			return "总价";
	}

	static String getWeekDay(char c) {
		switch (c) {
		case '1':
			return "周一";
		case '2':
			return "周二";
		case '3':
			return "周三";
		case '4':
			return "周四";
		case '5':
			return "周五";
		case '6':
			return "周六";
		default:
			return "周日";
		}
	}

	public static String httpUrlRequest(String requestURL, String json) {
		URL url;
		String response = "";
		HttpURLConnection connection = null;
		InputStream is = null;
		try {
			url = new URL(requestURL);

			connection = (HttpURLConnection) url.openConnection();
			connection.setDoOutput(true);
			connection.setRequestMethod("POST");
			connection.getOutputStream().write(json.getBytes());
			connection.getOutputStream().flush();
			connection.getOutputStream().close();
			int code = connection.getResponseCode();
			System.out.println("code" + code);

		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			connection.disconnect();
		}
		return response;
	}

	public static void main(String[] args) {
		Gson gson = new Gson();
		Map<String, Object> tempMap = gson.fromJson(json,
				new TypeToken<Map<String, Object>>() {
				}.getType());
		// tempMap.put("GetOn", getDateString());

		httpUrlRequest("http://221.181.64.226:9000", json);
	}

	static String getDateString() {
		Date date = new Date();
		SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd,HH:mm:ss");
		String dateString = df.format(getNextDay(date));
		return dateString;
	}

	private static Date getNextDay(Date date) {
		Calendar calendar = Calendar.getInstance();
		calendar.setTime(date);
		calendar.add(Calendar.DAY_OF_MONTH, -1);
		date = calendar.getTime();
		return date;
	}

	private static String json = "{\r\n"
			+ "\"Created\":\"ISODate('2013-07-03T04:32:01.540Z')\",\r\n"
			+ "\"Version\":\"2\",\r\n" + "\"TaxiStation\":\"0302\",\r\n"
			+ "\"TaxiLicense\":\"QW7391\",\r\n" + "\"System_ID\":\"802\",\r\n"
			+ "\"SWVersion\":\"rel_touchbox_20101010\",\r\n"
			+ "\"PackageVersion\":\"201211270027\",\r\n"
			+ "\"PublishVersion\":\"201212311450.xml\",\r\n"
			+ "\"GetOn\":\"2013-01-20,11:32:22\",\r\n"
			+ "\"GetOnCellInfo\":\"46000,101c,801a\",\r\n"
			+ "\"GetOnLongitude\":\"121.437363\",\r\n"
			+ "\"GetOnLatitude\":\"31.235067\",\r\n" + "\"display\":{\r\n"
			+ "\"0\":{\r\n" + "\"Name\":\"LOG-11-05-SH-O1.mp4\",\r\n"
			+ "\"Type\":\"Startup\",\r\n" + "\"Count\":\"2\",\r\n"
			+ "\"Detail\":{\r\n" + "\"0\":{\r\n"
			+ "\"Start\":\"2013-01-20,11:32:22\",\r\n"
			+ "\"End\":\"2013-01-20,11:32:30\"\r\n" + "},\r\n" + "\"1\":{\r\n"
			+ "\"Start\":\"2013-01-20,11:32:22\",\r\n"
			+ "\"End\":\"2013-01-20,11:32:30\"\r\n" + "}\r\n" + "}\r\n"
			+ "},\r\n" + "\"1\":{\r\n"
			+ "\"Name\":\"PUR-12-47-SH-X3.mp4\",\r\n"
			+ "\"Type\":\"Movie\",\r\n" + "\"Count\":\"2\",\r\n"
			+ "\"Detail\":{\r\n" + "\"0\":{\r\n"
			+ "\"Start\":\"2013-01-20,11:32:22\",\r\n"
			+ "\"End\":\"2013-01-20,11:32:30\"\r\n" + "},\r\n" + "\"1\":{\r\n"
			+ "\"Start\":\"2013-01-20,11:32:22\",\r\n"
			+ "\"End\":\"2013-01-20,11:32:30\"\r\n" + "}\r\n" + "}\r\n"
			+ "},\r\n" + "\"2\":{\r\n"
			+ "\"Name\":\"AMR-12-01-BJ-X2.jpg\",\r\n" + "\"Type\":\"JPG\",\r\n"
			+ "\"Count\":\"1\",\r\n" + "\"Detail\":{\r\n" + "\"0\":{\r\n"
			+ "\"Start\":\"2013-01-20,11:32:22\",\r\n"
			+ "\"End\":\"2013-01-20,11:32:30\"\r\n" + "}\r\n" + "}\r\n"
			+ "},\r\n" + "\"3\":{\r\n"
			+ "\"Name\":\"QDL-13-01-BJ-X2-R2.zip\",\r\n"
			+ "\"Type\":\"Html\",\r\n" + "\"Count\":\"1\",\r\n"
			+ "\"Detail\":{\r\n" + "\"0\":{\r\n"
			+ "\"Start\":\"2013-01-20,11:32:22\",\r\n"
			+ "\"End\":\"2013-01-20,11:32:30\"\r\n" + "}\r\n" + "}\r\n"
			+ "},\r\n" + "\"4\":{\r\n"
			+ "\"Name\":\"QDL-13-01-BJ-X2-R3.zip\",\r\n"
			+ "\"Type\":\"Html\",\r\n" + "\"Count\":\"2\",\r\n"
			+ "\"Detail\":{\r\n" + "\"0\":{\r\n"
			+ "\"Start\":\"2013-01-20,11:32:22\",\r\n"
			+ "\"End\":\"2013-01-20,11:32:30\",\r\n" + "\"Option\":{\r\n"
			+ "\"Count\":\"4\",\r\n" + "\"Detail\":{\r\n" + "\"0\":{\r\n"
			+ "\"Restaurant\":\"2013-01-20,11:32:42\"\r\n" + "},\r\n"
			+ "\"1\":{\r\n" + "\"Entertain\":\"2013-01-20,11:32:43\"\r\n"
			+ "},\r\n" + "\"2\":{\r\n"
			+ "\"Restaurant\":\"2013-01-20,11:32:42\"\r\n" + "},\r\n"
			+ "\"3\":{\r\n" + "\"Ent#d2\":\"2013-01-20,11:33:01\"\r\n"
			+ "}\r\n" + "}\r\n" + "}\r\n" + "},\r\n" + "\"1\":{\r\n"
			+ "\"Start\":\"2013-01-20,11:36:22\",\r\n"
			+ "\"End\":\"2013-01-20,11:36:30\",\r\n" + "\"Option\":{\r\n"
			+ "\"Count\":\"3\",\r\n" + "\"Detail\":{\r\n" + "\"0\":{\r\n"
			+ "\"Restaurant\":\"2013-01-20,11:32:42\"\r\n" + "},\r\n"
			+ "\"1\":{\r\n" + "\"Entertain\":\"2013-01-20,11:32:43\"\r\n"
			+ "},\r\n" + "\"2\":{\r\n"
			+ "\"Ent#d2\":\"2013-01-20,11:33:01\"\r\n" + "}\r\n" + "}\r\n"
			+ "}\r\n" + "}\r\n" + "}\r\n" + "},\r\n" + "\"5\":{\r\n"
			+ "\"Name\":\"SetVolume\",\r\n" + "\"Type\":\"Setup\",\r\n"
			+ "\"Count\":\"4\",\r\n" + "\"Detail\":{\r\n" + "\"0\":{\r\n"
			+ "\"High\":\"2013-01-20,11:33:27\"\r\n" + "},\r\n" + "\"1\":{\r\n"
			+ "\"Mute\":\"2013-01-20,11:39:27\"\r\n" + "},\r\n" + "\"2\":{\r\n"
			+ "\"Low\":\"2013-01-20,11:40:27\"\r\n" + "},\r\n" + "\"3\":{\r\n"
			+ "\"High\":\"2013-01-20,11:41:02\"\r\n" + "}\r\n" + "}\r\n"
			+ "},\r\n" + "\"6\":{\r\n" + "\"Name\":\"SwitchScreen\",\r\n"
			+ "\"Type\":\"Screen\",\r\n" + "\"Count\":\"2\",\r\n"
			+ "\"Detail\":{\r\n" + "\"0\":{\r\n"
			+ "\"Off\":\"2013-01-20,11:40:27\"\r\n" + "},\r\n" + "\"1\":{\r\n"
			+ "\"On\":\"2013-01-20,11:40:29\"\r\n" + "}\r\n" + "}\r\n"
			+ "}\r\n" + "},\r\n" + "\"GetOff\":\"2013-01-20,11:57:46\",\r\n"
			+ "\"GetOffCellInfo\":\"46000,101d,7805\",\r\n"
			+ "\"MeterSID\":\"1075314688\",\r\n"
			+ "\"MeterGetOnTime\":\"19:05\",\r\n"
			+ "\"MeterGetOffTime\":\"19:20\",\r\n"
			+ "\"MeterMile\":\"6.1\",\r\n"
			+ "\"MeterWaitTime\":\"00:05:33\",\r\n"
			+ "\"MeterCharged\":\"19.00\",\r\n"
			+ "\"Survey\":\"TP_13666666666-1\",\r\n" + "\"Undisplay\":{\r\n"
			+ "\"0\":\"QDL-13-01-BJ-X2-R1.zip\",\r\n"
			+ "\"1\":\"QDL-13-01-BJ-X2-R2.zip\",\r\n"
			+ "\"2\":\"QDL-13-01-BJ-X2-R3.zip\",\r\n"
			+ "\"3\":\"QDL-13-01-BJ-X2-R4.zip\",\r\n"
			+ "\"4\":\"QDL-13-01-BJ-X2-R5.zip\"\r\n" + "}\r\n" + "}";

}
