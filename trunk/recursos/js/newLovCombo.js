function newLovCombo(target, max, className) {
	var lovCombo = this;
	
	if (!className)
		className = "";
	
	lovCombo.isVisible = false;
	
	lovCombo.numOptions = 0;
	lovCombo.options = new Array();
	
	lovCombo.maxValues = max;
	lovCombo.values = 0;
	
	lovCombo.target = target;
	lovCombo.target.lovCombo = lovCombo;
	lovCombo.target.setAttribute("readOnly", "true");
	
	lovCombo.target.value = "";

	lovCombo.topOffset = lovCombo.target.offsetHeight;
	lovCombo.leftOffset = 0;
	
	lovCombo.iframe =  document.createElement("iframe");
	lovCombo.iframe.setAttribute("class", "newLovCombo " + className);
	lovCombo.iframe.setAttribute("className", "newLovCombo " + className);
	lovCombo.iframe.style.display = "none";

	lovCombo.content = document.createElement("div");
	lovCombo.content.setAttribute("class", "newLovCombo " + className);
	lovCombo.content.setAttribute("className", "newLovCombo " + className);
	lovCombo.content.style.display = "none";
	this.updatePos();
	
	document.body.appendChild(lovCombo.iframe);
	document.body.appendChild(lovCombo.content);
	
	this.showCount();
	
	lovCombo.target.onfocus = function() {
		if (lovCombo.isVisible == false)
			lovCombo.show();
	}
	
	lovCombo.content.onmouseout = function() {
		lovCombo.target.focus();
	}
	
	lovCombo.mousein = false;
	
	addEventHandler(lovCombo.content,'mouseover', onmouseover);
	addEventHandler(lovCombo.content,'mouseout', onmouseout);
	addEventHandler(lovCombo.target,'blur', popupBlur);
	
	//------------------------------------------------

	lovCombo.hide = function() {
		this.iframe.style.display = "none";
		this.content.style.display = "none";
		lovCombo.isVisible = false;
	}

	lovCombo.show = function() {
		this.updatePos();
		this.iframe.style.display = "block";
		this.content.style.display = "block";
		lovCombo.isVisible = true;
	}
	
	lovCombo.refreshCount = function() {
		this.content.removeChild(this.content.childNodes[0]);
		this.showCount();
	}
	
	lovCombo.addOption = function(value, text) {
		var i;
		for (i=0; i<this.numOptions; i++)
			if (this.options[i].value == value)
				return;
	
		var lb = document.createElement("label");
		if (this.numOptions % 2 == 0) {
			lb.setAttribute("class", "labelPar");
			lb.setAttribute("className", "labelPar");
		} else {
			lb.setAttribute("class", "labelImpar");
			lb.setAttribute("className", "labelImpar");
		}
		lb.setAttribute("title", text);
		var ch = document.createElement("input");
		ch.setAttribute("type", "checkbox");
		ch.setAttribute("value", value);

		if (textWidth(text) > this.target.scrollWidth-40) {
			while (textWidth(text)+textWidth("...") > this.target.scrollWidth-40)
				text = text.substring(0, text.length-1);
			text += "...";
		}
		
		var tt = document.createTextNode(text);
		
		lb.appendChild(ch);
		lb.appendChild(tt);
		
		ch.onclick = function() {
			if (this.checked == true)
				lovCombo.addValue(this.value);
			else
				lovCombo.removeValue(this.value);
			
			lovCombo.target.focus();
		}
		
		ch.onfocus = function() {
			lovCombo.target.focus();
		}
		
		this.content.appendChild(lb);
		this.options[this.numOptions] = ch;
		this.numOptions ++;
	}

	lovCombo.addValue = function(value) {
		if (this.target.value != "") {
			value = ", " + value;
		}
		this.target.value = this.target.value + value;
		this.values ++;
		
		if (this.values == this.maxValues)
			this.disable();
		lovCombo.refreshCount();
	}
	
	lovCombo.removeValue = function(value) {
		if (this.target.value.indexOf(value) == 0)
			this.target.value = this.target.value.replace(value + ", ", "");
		else
			this.target.value = this.target.value.replace(", " + value, "");
			
		this.target.value = this.target.value.replace(value, "");
		
		if (this.values == this.maxValues)
			this.enable();
		
		this.values --;
		
		lovCombo.refreshCount();
	}

	lovCombo.getValues = function() {
		var arr = this.target.value.split(', ');
		return arr;
	}
	
	lovCombo.selectValue = function(value) {
		var i;
		for (i=0; i<this.numOptions; i++)
			if (this.options[i].value == value) {
				if (this.options[i].checked == false) {
					this.options[i].checked = true;
					this.addValue(value);
				}
			}
	}
	
	lovCombo.disable = function() {
		var i;
		for (i=0; i<this.numOptions; i++)
			if (this.options[i].checked == false) {
				this.options[i].disabled = true;
			}
	}
	
	lovCombo.enable = function() {
		var i;
		for (i=0; i<this.numOptions; i++)
			if (this.options[i].disabled == true) {
				this.options[i].disabled = false;
			}
	}

	//-------------------------------------------------	
	
	function onmouseover() {
		lovCombo.mousein = true;
	}

	function onmouseout()	{
		lovCombo.mousein = false;
	}

	function popupBlur() {
		if(!lovCombo.mousein){
			lovCombo.hide();
		}
	}
	
}

newLovCombo.prototype.showCount = function() {
	var lb = document.createElement("label");
	lb.setAttribute("class", "labelCount");
	lb.setAttribute("className", "labelCount");
	var tt = document.createTextNode("" + this.values + " / " + this.maxValues + " - (" + this.numOptions + ")");
	lb.appendChild(tt);
	this.content.insertBefore(lb, this.content.firstChild);
}

newLovCombo.prototype.updatePos = function() {
	this.iframe.style.top = getTop(this.target) + this.topOffset + "px";
	this.iframe.style.left = getLeft(this.target) + this.leftOffset + 'px';
	this.iframe.style.width = this.target.offsetWidth.toString() + 'px';
	
	this.content.style.top = getTop(this.target) + this.topOffset + "px";
	this.content.style.left = getLeft(this.target) + this.leftOffset + 'px';
	this.content.style.width = this.target.offsetWidth.toString() + 'px';
}

function createLovCombo(target, max, className) {
	var lC = new newLovCombo(target, max, className);

	return lC;
}

