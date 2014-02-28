package com.sungtech.goodTeacher.action;

import java.io.InputStream;
import java.util.Arrays;
import java.util.Date;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import org.apache.struts2.ServletActionContext;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.opensymphony.xwork2.ActionContext;
import com.opensymphony.xwork2.ActionSupport;
import com.sungtech.goodTeacher.pojo.ResultData;
import com.sungtech.goodTeacher.service.WeiXinService;
import com.sungtech.goodTeacher.util.Util;

public class WeiXinAction extends ActionSupport {
	private static final long serialVersionUID = 1L;
	private final static Logger log = LoggerFactory
			.getLogger(WeiXinAction.class);
	private final static String TOKEN = "goodTeacher";

	private final static String POST_REQUEST = "post";

	private final static String GET_REQUEST = "get";

	private String signature;

	private String timestamp;

	private String nonce;

	private String echostr;

	public String getSignature() {
		return signature;
	}

	public void setSignature(String signature) {
		this.signature = signature;
	}

	public String getTimestamp() {
		return timestamp;
	}

	public void setTimestamp(String timestamp) {
		this.timestamp = timestamp;
	}

	public String getNonce() {
		return nonce;
	}

	public void setNonce(String nonce) {
		this.nonce = nonce;
	}

	public String getEchostr() {
		return echostr;
	}

	public void setEchostr(String echostr) {
		this.echostr = echostr;
	}

	@Override
	public String execute() throws Exception {
		ActionContext ctx = ActionContext.getContext();
		HttpServletRequest request = (HttpServletRequest) ctx
				.get(ServletActionContext.HTTP_REQUEST);
		HttpServletResponse response = (HttpServletResponse) ctx
				.get(ServletActionContext.HTTP_RESPONSE);

		String method = request.getMethod();
		HttpSession session = request.getSession();
		this.getCategoryList(session);

		String xml = null;
		if (POST_REQUEST.equalsIgnoreCase(method)) {
			xml = messageProcess(request, response);
			response.setCharacterEncoding("UTF-8");
			response.getWriter().print(xml);
		} else if (GET_REQUEST.equalsIgnoreCase(method)) {
			validateSignature(request, response);
		}

		return null;
	}

	private String messageProcess(HttpServletRequest request,
			HttpServletResponse response) throws Exception {
		InputStream inputStream = request.getInputStream();
		String userMessage = Util.inputStream2String(inputStream);
		if (log.isDebugEnabled())
			log.debug(" --- POST request : \r\n {} \r\n", userMessage);
		HttpSession session = request.getSession();
		session.setMaxInactiveInterval(900);

		WeiXinService weiXinService = new WeiXinService();
		return weiXinService.UserMessageProcess(userMessage, session);
	}

	private void validateSignature(HttpServletRequest request,
			HttpServletResponse response) throws Exception {
		if (log.isDebugEnabled())
			log.debug(" --- validateSignature request ");
		String[] ArrTmp = { TOKEN, timestamp, nonce };
		Arrays.sort(ArrTmp);
		StringBuffer sb = new StringBuffer();
		for (int i = 0; i < ArrTmp.length; i++) {
			sb.append(ArrTmp[i]);
		}
		String pwd = Util.encrypt(sb.toString());
		if (pwd.equals(signature)) {
			if (!"".equals(echostr) && echostr != null) {
				response.getWriter().print(echostr);
			}
		}
	}

	private void getCategoryList(HttpSession session) throws Exception {
		Date getCategoryTime = (Date) session.getAttribute("getCategoryTime");
		int twoDateDifference = 25;
		if (getCategoryTime != null) {
			twoDateDifference = (int) ((new Date().getTime() - getCategoryTime
					.getTime()) / 3600000);
		}

		if (twoDateDifference > 24) {
			String totalCategory = Util
					.httpUrlRequest("http://www.kaopuu.com/gtapi/app/get_category_list");
			Gson gson = new Gson();
			ResultData categoryMap = gson.fromJson(totalCategory,
					new TypeToken<ResultData>() {
					}.getType());
			session.setAttribute("categoryList", categoryMap.getData());
		}
	}

	public static void main(String[] args) {
		String totalCategory = Util
				.httpUrlRequest("http://www.kaopuu.com/gtapi/app/get_category_list");
		Gson gson = new Gson();
		ResultData categoryMap = gson.fromJson(totalCategory,
				new TypeToken<ResultData>() {
				}.getType());
	}

}
