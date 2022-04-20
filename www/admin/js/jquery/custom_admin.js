//-----------------------------------------
// Author: Fluierari Ghenadie
// Web: http://www.ebs.md
// Company : EBS
//-----------------------------------------
        function check_field(title1)
         {
           var input = 0;  
           var nr = $('.form').find('.request').length;
           for (i=0;i<nr;i++)
           {
               if($('.form').find('.request').eq(i).val()) var input = input + 1;
           }          
           if(nr != input )
           {
               alert(title1);
               return false;
           }
           else
           {              
               return true;
           }
         } 
         
      
         
        function check_field_pwd(title1,pwd_text)
         {
           var input = 0;  
           var nr = $('.form').find('.request').length;
           for (i=0;i<nr;i++)
           {
               if($('.form').find('.request').eq(i).val()) var input = input + 1;
           }          
           if(nr != input )
           {
               alert(title1);
               var status = false;
           }
           else
           {
               var status = true;
           }
           
           var pwd = $('.pwd').val();
           var conf_pwd = $('.conf_pwd').val();
           if(status==true)
           {    
               if((pwd != conf_pwd) || conf_pwd=='' || pwd =='' )
               {
                   alert(pwd_text);
                   var status = false;
               }
               else
               {
                   var status = true;
               }
           }
           return status;
           
           
         } 
         
         /*
        function delete_row(table,msg)
        {
               switch (table) {
                    case 'langs':
                        {
                           $('.list').find("input[name='selected[]']:checked").each(function(i) {
                                    var id = $(this).val();
                                    $(this).parent('td').parent('tr').remove();
                                    $.post("/systems/del_langs" , {"id" : id}, function(data){});                                    
                           });
                           $("#msg").html('<div class=succes>'+msg+'</div>').css({opacity:1}).delay(4000).animate({opacity:0},500,function(){$(this).remove();});
                           break;
                        }
                    default:;
              }
        } */
        function delete_row(table,msg,src)
        {               
               $('.list').find("input[name='selected[]']:checked").each(function(i) {
                        var id = $(this).val();
                        $(this).parent('td').parent('tr').remove();
                        $.post(src , {"id" : id}, function(data){});                                    
               });
               $("#msg").html('<div class=succes>'+msg+'</div>').css({opacity:1}).delay(4000).animate({opacity:0},500,function(){$(this).remove();});
                           
        } 
        
$(document).ready(function(){
    
 var succes=$(".succes").html();
 if (succes) $(".succes").css({opacity:1}).delay(4000).animate({opacity:0},500,function(){$(this).remove();});
 
 var warning=$(".warning").html();
 if (warning) $(".warning").css({opacity:1}).delay(4000).animate({opacity:0},function(){$(this).remove();});

// meniu de sus
	var url = String(document.location).toLowerCase().split('/');
	var lung =  url.length;
        var route = url[lung-1];
       
        switch (route) {
                case 'langs':
                    {
                        var route = 'system';
                        break;
                    }
                case 'langs_form':
                    {
                        var route = 'system';
                        break;
                    }
                case 'users':
                    {
                        var route = 'system';
                        break;
                    }
                case 'logs':
                    {
                        var route = 'system';
                        break;
                    }
                case 'vars':
                    {
                        var route = 'system';
                        break;
                    }
                case 'gallery':
                    {
                        var route = 'gallery';
                        break;
                    }
                case 'vars_form':
                    {
                        var route = 'system';
                        break;
                    }
                case 'settings':
                    {
                        var route = 'system';
                        break;
                    }
                case 'users_form':
                    {
                        var route = 'system';
                        break;
                    }
                case 'pages_form':
                    {
                        var route = 'pages';
                        break;
                    }
                case 'catalog':
                    {
                        var route = 'catalog';
                        break;
                    }                    
                case 'catalog_form':
                    {
                        var route = 'catalog';
                        break;
                    }
                case 'pages':
                    {
                        var route = 'pages';
                        break;
                    }
                case 'left_menu':
                    {
                        var route = 'left_menu';
                        break;
                    }
                case 'left_menu_from':
                    {
                        var route = 'left_menu';
                        break;
                    }
                case 'category':
                    {
                        var route = 'catalog';
                        break;
                    }
               case 'category_form':
                    {
                        var route = 'catalog';
                        break;
                    }
               case 'products':
                    {
                        var route = 'products';
                        break;
                    }
               case 'products_form':
                    {
                        var route = 'products';
                        break;
                    }
                    
               case 'video':
                    {
                        var route = 'video';
                        break;
                    }
               case 'video_form':
                    {
                        var route = 'video';
                        break;
                    }                    
                    
                    
               case 'command':
                    {
                        var route = 'command';
                        break;
                    }
               case 'command_form':
                    {
                        var route = 'command';
                        break;
                    }                      
                    
                case 'orders':
                    {
                        var route = 'sale';
                        break;
                    }
                case 'orders_from':
                    {
                        var route = 'sale';
                        break;
                    }
                case 'offerts':
                    {
                        var route = 'sale';
                        break;
                    }
                case 'products_from':
                    {
                        var route = 'products';
                        break;
                    }
                case 'products':
                    {
                        var route = 'products';
                        break;
                    }                    
                default:;
        }            
        
        $('#menu ul li').removeClass('selected');            
	$('#'+route).addClass('selected');

//  drop down menu
	$('.nav').superfish({
		hoverClass	 : 'sfHover',
		pathClass	 : 'overideThisToUse',
		delay		 : 0,
		animation	 : {height: 'show'},
		speed		 : 'normal',
		autoArrows   : false,
		dropShadows  : false, 
		disableHI	 : false, /* set to true to disable hoverIntent detection */
		onInit		 : function(){},
		onBeforeShow : function(){},
		onShow		 : function(){},
		onHide		 : function(){}
	});
	
	$('.nav').css('display', 'block');
        
//cautare 
    $('#filtru').click(function()
    {
        $('#search').show();
        $('#filtru').hide();
    });
    $('#cancel_filtru').click(function()
    {
        $('#search').hide();
        $('#filtru').show();
    });
    

});
