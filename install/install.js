function validatedb(){var g,f,j,i,h;g=document.getElementById("installform");f=g.dbhost.value;j=g.dbname.value;i=g.dbuser.value;h=g.dbpass.value;if(f==""){alert("Please enter a valid Database Host")}else{if(j==""){alert("Please enter a valid Database Name")}else{if(i==""){alert("Please enter a valid Database User")}else{if(h==""){if(i=="root"){return true}else{alert("Please enter a valid Database Password")}}else{return true}}}}return false}function validateuser(){var f,e,h,g;f=document.getElementById("installform");e=f.admname.value;h=f.admpass.value;g=f.admpass2.value;if(e==""){alert("Please enter a valid Admin Name")}else{if(h==""){alert("Please enter a valid Password")}else{if(h!=g){alert("Passwords Mismatch! Please enter same password to confirm.")}else{return true}}}return false}function gotoAdmin(){window.location.href="../"};