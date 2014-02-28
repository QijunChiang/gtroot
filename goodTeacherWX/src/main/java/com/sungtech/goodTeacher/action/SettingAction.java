package com.sungtech.goodTeacher.action;

import com.opensymphony.xwork2.ActionSupport;
import com.sungtech.goodTeacher.service.WeiXinService;

public class SettingAction extends ActionSupport {
	private static final long serialVersionUID = 1L;
	private String msg;
	private int limit;

	public int getLimit() {
		return limit;
	}

	public void setLimit(int limit) {
		this.limit = limit;
	}

	public String getMsg() {
		return msg;
	}

	public void setMsg(String msg) {
		this.msg = msg;
	}

	@Override
	public String execute() throws Exception {
		WeiXinService.setPageSize(limit);
		this.msg = "参数设置成功";
		return SUCCESS;
	}
}
