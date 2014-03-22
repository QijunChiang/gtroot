package com.sungtech.goodTeacher.action;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.commons.lang3.StringUtils;
import org.apache.struts2.interceptor.ServletRequestAware;
import org.apache.struts2.interceptor.ServletResponseAware;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.opensymphony.xwork2.ActionSupport;

public class CourseSignupAction extends ActionSupport implements
		ServletRequestAware, ServletResponseAware {
	private static final long serialVersionUID = 8720620384257328947L;
	final static Logger log = LoggerFactory
			.getLogger("com.sungtech.goodTeacher.UserSignUp");
	static final ThreadLocal<HttpServletRequest> request = new ThreadLocal<HttpServletRequest>();
	static final ThreadLocal<HttpServletResponse> response = new ThreadLocal<HttpServletResponse>();
	String msg;

	public String getMsg() {
		return msg;
	}

	public void setMsg(String msg) {
		this.msg = msg;
	}

	@Override
	public String execute() throws Exception {
		HttpServletRequest req = request.get();
		// HttpServletResponse resp = response.get();
		if (req == null)
			return ERROR;
		String courseId = req.getParameter("courseId");
		String teacherId = req.getParameter("teacherId");
		String userName = req.getParameter("userName");
		String phoneNo = req.getParameter("phoneNo");
		String wxOpenId = req.getParameter("wxOpenId");
		String teacherName = req.getParameter("teacherName");
		String courseName = req.getParameter("courseName");
		if (StringUtils.isEmpty(teacherId) || StringUtils.isEmpty(courseId)
				|| StringUtils.isEmpty(userName)
				|| StringUtils.isEmpty(phoneNo)
				|| StringUtils.isEmpty(wxOpenId)) {
			this.msg = "输入信息不合法，不能为空！";
			return SUCCESS;
		}
		if (log.isDebugEnabled())
			log.debug("教师:{},课程:《{}》,联系人:{},电话:{}", new Object[] { teacherName,
					courseName, userName, phoneNo });
		// if (log.isDebugEnabled())
		// log.debug("teacher:{},course:{},user:{},phone:{},openId:{}",
		// new Object[] { teacherId, courseId, userName, phoneNo,
		// wxOpenId });
		// resp
		// .sendRedirect(Util.WX_URL+"/teacherInfo?openId="
		// + wxOpenId + "&userId=" + teacherId);
		// resp.getOutputStream().close();
		this.msg = "参加报名成功";
		request.set(null);
		response.set(null);
		return SUCCESS;
	}

	@Override
	public void setServletRequest(HttpServletRequest req) {
		request.set(req);
	}

	@Override
	public void setServletResponse(HttpServletResponse resp) {
		response.set(resp);
	}

}
