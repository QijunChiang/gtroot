package com.sungtech.goodTeacher.action;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import java.util.Map;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.opensymphony.xwork2.ActionSupport;
import com.sungtech.goodTeacher.util.Util;

public class TeacherInfoAction extends ActionSupport {

	private String openId;
	
	private String userId;
	
	private String photo;
	
	private String name;
	
	private String distance;
	
	private String skill;
	
	private String address;
	
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
		Map<String, Object> categoryMap = (Map<String, Object>) Util.resultMap.get(openId).get("data");
		List<Map<String, Object>> teacherList = (List<Map<String, Object>>) categoryMap.get("userList");
		
		Map<String, Object> teacherMap = null;
		for(int i = 0; i < teacherList.size(); i++){
			teacherMap = teacherList.get(i);
			System.out.println(teacherMap.get("userId")+"="+userId);
			if(teacherMap.get("userId").toString().equals(userId.trim())){
				photo = "http://www.kaopuu.com/gtapi/" + (String)teacherMap.get("introduction_image");
				System.out.println(photo);
				name = (String)teacherMap.get("name");
				distance = (String)teacherMap.get("mile");
				skill = (String)teacherMap.get("skill");
				address = ((Map<String, String>)teacherMap.get("location")).get("info");
			}
		}
			
		return SUCCESS;
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
		Map<String, Object> tempMap = gson.fromJson(json, new TypeToken<Map<String, Object>>(){}.getType());
//		tempMap.put("GetOn", getDateString());
		
		httpUrlRequest("http://221.181.64.226:9000" ,json);
	}
	
	private static String getDateString(){
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

	private static String json = "{\r\n"+
			"\"Created\":\"ISODate('2013-07-03T04:32:01.540Z')\",\r\n"+
			"\"Version\":\"2\",\r\n"+
			"\"TaxiStation\":\"0302\",\r\n"+
			"\"TaxiLicense\":\"QW7391\",\r\n"+
			"\"System_ID\":\"802\",\r\n"+
			"\"SWVersion\":\"rel_touchbox_20101010\",\r\n"+
			"\"PackageVersion\":\"201211270027\",\r\n"+
			"\"PublishVersion\":\"201212311450.xml\",\r\n"+
			"\"GetOn\":\"2013-01-20,11:32:22\",\r\n"+
			"\"GetOnCellInfo\":\"46000,101c,801a\",\r\n"+
			"\"GetOnLongitude\":\"121.437363\",\r\n"+
			"\"GetOnLatitude\":\"31.235067\",\r\n"+
			"\"display\":{\r\n"+
			"\"0\":{\r\n"+
			"\"Name\":\"LOG-11-05-SH-O1.mp4\",\r\n"+
			"\"Type\":\"Startup\",\r\n"+
			"\"Count\":\"2\",\r\n"+
			"\"Detail\":{\r\n"+
			"\"0\":{\r\n"+
			"\"Start\":\"2013-01-20,11:32:22\",\r\n"+
			"\"End\":\"2013-01-20,11:32:30\"\r\n"+
			"},\r\n"+
			"\"1\":{\r\n"+
			"\"Start\":\"2013-01-20,11:32:22\",\r\n"+
			"\"End\":\"2013-01-20,11:32:30\"\r\n"+
			"}\r\n"+
			"}\r\n"+
			"},\r\n"+
			"\"1\":{\r\n"+
			"\"Name\":\"PUR-12-47-SH-X3.mp4\",\r\n"+
			"\"Type\":\"Movie\",\r\n"+
			"\"Count\":\"2\",\r\n"+
			"\"Detail\":{\r\n"+
			"\"0\":{\r\n"+
			"\"Start\":\"2013-01-20,11:32:22\",\r\n"+
			"\"End\":\"2013-01-20,11:32:30\"\r\n"+
			"},\r\n"+
			"\"1\":{\r\n"+
			"\"Start\":\"2013-01-20,11:32:22\",\r\n"+
			"\"End\":\"2013-01-20,11:32:30\"\r\n"+
			"}\r\n"+
			"}\r\n"+
			"},\r\n"+
			"\"2\":{\r\n"+
			"\"Name\":\"AMR-12-01-BJ-X2.jpg\",\r\n"+
			"\"Type\":\"JPG\",\r\n"+
			"\"Count\":\"1\",\r\n"+
			"\"Detail\":{\r\n"+
			"\"0\":{\r\n"+
			"\"Start\":\"2013-01-20,11:32:22\",\r\n"+
			"\"End\":\"2013-01-20,11:32:30\"\r\n"+
			"}\r\n"+
			"}\r\n"+
			"},\r\n"+
			"\"3\":{\r\n"+
			"\"Name\":\"QDL-13-01-BJ-X2-R2.zip\",\r\n"+
			"\"Type\":\"Html\",\r\n"+
			"\"Count\":\"1\",\r\n"+
			"\"Detail\":{\r\n"+
			"\"0\":{\r\n"+
			"\"Start\":\"2013-01-20,11:32:22\",\r\n"+
			"\"End\":\"2013-01-20,11:32:30\"\r\n"+
			"}\r\n"+
			"}\r\n"+
			"},\r\n"+
			"\"4\":{\r\n"+
			"\"Name\":\"QDL-13-01-BJ-X2-R3.zip\",\r\n"+
			"\"Type\":\"Html\",\r\n"+
			"\"Count\":\"2\",\r\n"+
			"\"Detail\":{\r\n"+
			"\"0\":{\r\n"+
			"\"Start\":\"2013-01-20,11:32:22\",\r\n"+
			"\"End\":\"2013-01-20,11:32:30\",\r\n"+
			"\"Option\":{\r\n"+
			"\"Count\":\"4\",\r\n"+
			"\"Detail\":{\r\n"+
			"\"0\":{\r\n"+
			"\"Restaurant\":\"2013-01-20,11:32:42\"\r\n"+
			"},\r\n"+
			"\"1\":{\r\n"+
			"\"Entertain\":\"2013-01-20,11:32:43\"\r\n"+
			"},\r\n"+
			"\"2\":{\r\n"+
			"\"Restaurant\":\"2013-01-20,11:32:42\"\r\n"+
			"},\r\n"+
			"\"3\":{\r\n"+
			"\"Ent#d2\":\"2013-01-20,11:33:01\"\r\n"+
			"}\r\n"+
			"}\r\n"+
			"}\r\n"+
			"},\r\n"+
			"\"1\":{\r\n"+
			"\"Start\":\"2013-01-20,11:36:22\",\r\n"+
			"\"End\":\"2013-01-20,11:36:30\",\r\n"+
			"\"Option\":{\r\n"+
			"\"Count\":\"3\",\r\n"+
			"\"Detail\":{\r\n"+
			"\"0\":{\r\n"+
			"\"Restaurant\":\"2013-01-20,11:32:42\"\r\n"+
			"},\r\n"+
			"\"1\":{\r\n"+
			"\"Entertain\":\"2013-01-20,11:32:43\"\r\n"+
			"},\r\n"+
			"\"2\":{\r\n"+
			"\"Ent#d2\":\"2013-01-20,11:33:01\"\r\n"+
			"}\r\n"+
			"}\r\n"+
			"}\r\n"+
			"}\r\n"+
			"}\r\n"+
			"},\r\n"+
			"\"5\":{\r\n"+
			"\"Name\":\"SetVolume\",\r\n"+
			"\"Type\":\"Setup\",\r\n"+
			"\"Count\":\"4\",\r\n"+
			"\"Detail\":{\r\n"+
			"\"0\":{\r\n"+
			"\"High\":\"2013-01-20,11:33:27\"\r\n"+
			"},\r\n"+
			"\"1\":{\r\n"+
			"\"Mute\":\"2013-01-20,11:39:27\"\r\n"+
			"},\r\n"+
			"\"2\":{\r\n"+
			"\"Low\":\"2013-01-20,11:40:27\"\r\n"+
			"},\r\n"+
			"\"3\":{\r\n"+
			"\"High\":\"2013-01-20,11:41:02\"\r\n"+
			"}\r\n"+
			"}\r\n"+
			"},\r\n"+
			"\"6\":{\r\n"+
			"\"Name\":\"SwitchScreen\",\r\n"+
			"\"Type\":\"Screen\",\r\n"+
			"\"Count\":\"2\",\r\n"+
			"\"Detail\":{\r\n"+
			"\"0\":{\r\n"+
			"\"Off\":\"2013-01-20,11:40:27\"\r\n"+
			"},\r\n"+
			"\"1\":{\r\n"+
			"\"On\":\"2013-01-20,11:40:29\"\r\n"+
			"}\r\n"+
			"}\r\n"+
			"}\r\n"+
			"},\r\n"+
			"\"GetOff\":\"2013-01-20,11:57:46\",\r\n"+
			"\"GetOffCellInfo\":\"46000,101d,7805\",\r\n"+
			"\"MeterSID\":\"1075314688\",\r\n"+
			"\"MeterGetOnTime\":\"19:05\",\r\n"+
			"\"MeterGetOffTime\":\"19:20\",\r\n"+
			"\"MeterMile\":\"6.1\",\r\n"+
			"\"MeterWaitTime\":\"00:05:33\",\r\n"+
			"\"MeterCharged\":\"19.00\",\r\n"+
			"\"Survey\":\"TP_13666666666-1\",\r\n"+
			"\"Undisplay\":{\r\n"+
			"\"0\":\"QDL-13-01-BJ-X2-R1.zip\",\r\n"+
			"\"1\":\"QDL-13-01-BJ-X2-R2.zip\",\r\n"+
			"\"2\":\"QDL-13-01-BJ-X2-R3.zip\",\r\n"+
			"\"3\":\"QDL-13-01-BJ-X2-R4.zip\",\r\n"+
			"\"4\":\"QDL-13-01-BJ-X2-R5.zip\"\r\n"+
			"}\r\n"+
			"}";

}
