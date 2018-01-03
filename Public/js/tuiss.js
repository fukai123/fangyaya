// JavaScript Document// JavaScript Document
window.onload=function(){
	  num=0;
	  var tab_t = document.getElementById("fl");
	  var tab_t_li = tab_t.getElementsByTagName("li");
	  var tab_c = document.getElementById("fl_cont");
	  var tab_c_li = tab_c.getElementsByTagName("div");
	  var tab_c_o = tab_c.getElementsByTagName("li");
  
  var len = tab_t_li.length;
  var i=0;
  for(i=0; i<len; i++){
   tab_t_li[i].index = i;
   tab_t_li[i].onclick = function(){
    for(i=0; i<len; i++){
     tab_t_li[i].className = '';
     tab_c_li[i].className = 'hide';
    }
    tab_t_li[this.index].className = 'demo';
    tab_c_li[this.index].className = '';
   }
  }
/*  ————————————————————————————————————————*/
	function fab(){
		for(i=0;i<tab_c_o.length;i++){
			tab_c_o[i].className="";
			}
	}
  
  for(var i=0;i<tab_c_o.length;i++){
	  tab_c_o[i].onclick=function(){
			num=this.index;
			fab();
			this.className="demo_f";
	  }
	  }
	
	//课程
	  var f2 = document.getElementById("f2");
	  var f2_li = f2.getElementsByTagName("li");
	  var f2_cont = document.getElementById("f2_cont");
	  var f2_cont_div = f2_cont.getElementsByTagName("div");
	  var f2_cont_li = f2_cont.getElementsByTagName("li");
  
  var lenc = f2_li.length;
  var i=0;
  for(i=0; i<lenc; i++){
   f2_li[i].index = i;
   f2_li[i].onclick = function(){
    for(i=0; i<lenc; i++){
     f2_li[i].className = '';
     f2_cont_div[i].className = 'hide';
    }
    f2_li[this.index].className = 'demo';
    f2_cont_div[this.index].className = '';
   }
  }
	var f3=document.getElementById("f3");
	var f3_li_a=f3.getElementsByTagName("li");
	function fabc(){
		for(i=0;i<f2_cont_li.length;i++){
			f2_cont_li[i].className="";
			}
	}
  
  for(var i=0;i<f2_cont_li.length;i++){
	  f2_cont_li[i].onclick=function(){
			num=this.index;
			fabc();
			this.className="demo_f";
	  }
	  }
	
	
	//类型
	function fad_l(){
		for(i=0;i<f3_li_a.length;i++){
			f3_li_a[i].className="";
			}
		}
	
	for(var i=0;i<f3_li_a.length;i++){
		f3_li_a[i].onclick=function(){
			num=this.index;
			fad_l();
			this.className="demo";
			}
		}
	


}