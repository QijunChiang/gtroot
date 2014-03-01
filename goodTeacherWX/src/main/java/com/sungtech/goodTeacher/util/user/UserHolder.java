package com.sungtech.goodTeacher.util.user;

public class UserHolder {
	public static final String DEFULAT_LOCTION = "上海市静安区昌平路700号";
	public static final double LOCTION_X = 31.230415;
	public static final double LOCTION_Y = 121.473701;
	private static final long LEASE_TIME_MILLIS = 20 * 60 * 1000;
	private String fromUsername;
	private String courseId;
	private String chooseKey;
	private double locationX = LOCTION_X;
	private double locationY = LOCTION_Y;
	private volatile long timeToLive = LEASE_TIME_MILLIS;

	public UserHolder(String fromUser) {
		this.fromUsername = fromUser;
	}

	public String getFromUsername() {
		return fromUsername;
	}

	public void setFromUsername(String fromUsername) {
		this.fromUsername = fromUsername;
	}

	public String getCourseId() {
		return courseId;
	}

	public void setCourseId(String courseId) {
		this.courseId = courseId;
	}

	public double getLocationX() {
		return locationX;
	}

	public void setLocationX(String locationX) {
		this.locationX = Double.parseDouble(locationX);
	}

	public double getLocationY() {
		return locationY;
	}

	public void setLocationY(String locationY) {
		this.locationY = Double.parseDouble(locationY);
	}

	public String getChooseKey() {
		return chooseKey;
	}

	public void setChooseKey(String chooseKey) {
		this.chooseKey = chooseKey;
	}

	public void age(long aDeltaMillis) {
		timeToLive -= aDeltaMillis;
	}

	public boolean isExpired() {
		return timeToLive <= 0;
	}

	/**
	 * @see 重新开始计算失效时间
	 */
	public void kick() {
		timeToLive = LEASE_TIME_MILLIS;
	}
}
