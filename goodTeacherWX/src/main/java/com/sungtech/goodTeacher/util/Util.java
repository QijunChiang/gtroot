package com.sungtech.goodTeacher.util;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;
import java.util.regex.Pattern;

import org.apache.commons.lang3.StringUtils;

public class Util {
	public static final Map<String, Map<String, Object>> resultMap = new ConcurrentHashMap<String, Map<String, Object>>();

	public static String encrypt(String strSrc) {
		MessageDigest md = null;
		String strDes = null;

		byte[] bt = strSrc.getBytes();
		try {
			md = MessageDigest.getInstance("SHA-1");
			md.update(bt);
			strDes = bytes2Hex(md.digest()); // to HexString
		} catch (NoSuchAlgorithmException e) {
			System.out.println("Invalid algorithm.");
			return null;
		}
		return strDes;
	}

	public static String bytes2Hex(byte[] bts) {
		String des = "";
		String tmp = null;
		for (int i = 0; i < bts.length; i++) {
			tmp = (Integer.toHexString(bts[i] & 0xFF));
			if (tmp.length() == 1) {
				des += "0";
			}
			des += tmp;
		}
		return des;
	}

	public static String inputStream2String(InputStream is) throws Exception {
		InputStreamReader isr = new InputStreamReader(is);
		BufferedReader br = new BufferedReader(isr);
		StringBuffer sb = new StringBuffer();
		String s = null;
		while ((s = br.readLine()) != null) {
			sb.append(s);
		}
		return sb.toString();
	}

	public static String httpUrlRequest(String requestURL) {
		URL url;
		String response = "";
		HttpURLConnection connection = null;
		InputStream is = null;
		try {
			url = new URL(requestURL);
			connection = (HttpURLConnection) url.openConnection();
			is = connection.getInputStream();
			BufferedReader br = new BufferedReader(new InputStreamReader(is));
			StringBuffer sb = new StringBuffer();
			String line = null;
			while ((line = br.readLine()) != null) {
				sb.append(line + "\n");
			}
			response = sb.toString();
		} catch (Exception e) {
			System.out.println("Util.sendAndReceive()");
		} finally {
			try {
				is.close();
			} catch (IOException e) {
				System.out.println("Util.sendAndReceive()");
			}
			connection.disconnect();
		}
		return response;
	}

	public static String httpUrlRequestByPost(String requestURL, String param) {
		URL url;
		String response = "";
		HttpURLConnection connection = null;
		InputStream is = null;
		try {
			url = new URL(requestURL);
			connection = (HttpURLConnection) url.openConnection();
			connection.setRequestMethod("POST");
			connection.setDoOutput(true);
			byte[] bypes = param.toString().getBytes();
			connection.getOutputStream().write(bypes);// �������
			is = connection.getInputStream();
			BufferedReader br = new BufferedReader(new InputStreamReader(is));
			StringBuffer sb = new StringBuffer();
			String line = null;
			while ((line = br.readLine()) != null) {
				sb.append(line + "\n");
			}
			response = sb.toString();
		} catch (Exception e) {
			System.out.println("Util.sendAndReceive()");
		} finally {
			try {
				is.close();
			} catch (IOException e) {
				System.out.println("Util.sendAndReceive()");
			}
			connection.disconnect();
		}
		return response;
	}

	public static boolean isNumeric(String str) {
		Pattern pattern = Pattern.compile("[0-9]*");
		return pattern.matcher(str).matches();
	}

	public static void putResult(String fromUserName, Map<String, Object> result) {
		resultMap.put(fromUserName, result);
	}

	private final static double PI = 3.14159265358979323; // 圆周率
	private final static double R = 6371229; // 地球的半径

	public static double getDistance(double longt1, double lat1, double longt2,
			double lat2) {
		double x, y, distance;
		x = (longt2 - longt1) * PI * R
				* Math.cos(((lat1 + lat2) / 2) * PI / 180) / 180;
		y = (lat2 - lat1) * PI * R / 180;
		distance = Math.hypot(x, y);
		return distance;
	}

	public static String getInstance(String dis) {
		if (StringUtils.isEmpty(dis))
			return dis;
		try {
			int n = Integer.parseInt(dis);
			double d = formatDouble(n / 1000.0);
			return String.valueOf(d);
		} catch (Exception e) {
			e.printStackTrace();
			return dis;
		}
	}

	public static String getInstance(double dis) {
		try {
			double d = formatDouble(dis / 1000.0);
			return String.valueOf(d);
		} catch (Exception e) {
			e.printStackTrace();
			return String.valueOf(dis);
		}
	}

	public static double formatDouble(double d) {
		return (double) Math.round(d * 100) / 100;
	}

}
