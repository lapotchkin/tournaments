!function(t){var e={};function a(n){if(e[n])return e[n].exports;var r=e[n]={i:n,l:!1,exports:{}};return t[n].call(r.exports,r,r.exports,a),r.l=!0,r.exports}a.m=t,a.c=e,a.d=function(t,e,n){a.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},a.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},a.t=function(t,e){if(1&e&&(t=a(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(a.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)a.d(n,r,function(e){return t[e]}.bind(null,r));return n},a.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return a.d(e,"a",e),e},a.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},a.p="/",a(a.s=6)}({6:function(t,e,a){t.exports=a("GGTS")},GGTS:function(t,e){window.TRNMNT_gameFormModule=function(){var t=!1,e=null,a=null,n=null,r=null,o=null,s=null,l=null,i=null,c=null,d=null,p=null,u=null,f={game:'\n            <tr>\n                <td>#{date}</td>\n                <td class="text-right">#{home_team}</td>\n                <td class="text-right">\n                    <span class="badge badge-primary badge-pill">#{home_score}</span>\n                </td>\n                <td class="text-center">:</td>\n                <td>\n                    <span class="badge badge-primary badge-pill">#{away_score}</span>\n                </td>\n                <td>#{away_team}</td>\n                <td class="text-right">\n                    <button type="button" class="btn btn-primary btn-sm">Заполнить</button>\n                </td>\n            </tr>',player:'\n            <tr data-id="#{id}">\n                <td>#{tag}</td>\n                <td class="text-center">#{position}</td>\n                <td class="text-center">#{goals}</td>\n                <td class="text-center">#{assists}</td>\n                <td class="text-center text-nowrap">#{stars}</td>\n                <td></td>\n            </tr>',playerForm:'\n            <tr data-id="#{id}" style="#{style}">\n                <td>#{player}</td>\n                <td class="text-center">#{position}</td>\n                <td><input type="text" class="text-right form-control" name="goals" value="#{goals}"></td>\n                <td><input type="text" class="text-right form-control" name="assists" value="#{assists}"></td>\n                <td>#{stars}</td>\n                <td class="text-nowrap">#{button}</td>\n            </tr>\n            '};return{init:function(l,y,v,b,h){if(t)return;var _=TRNMNT_helpers.parseUrl();t=!0,i=l,p=b,u=v,c="playoff"===_.segments[2]?+_.segments[4]:null,d="playoff"===_.segments[2]?"add"===_.segments[5]?null:+TRNMNT_helpers.parseUrl().segments[5]:+TRNMNT_helpers.parseUrl().segments[4];console.log("_pairId",c),console.log("_gameId",d),e=$("#eaGames"),a=$("#getGames"),n=$("#resetGame"),r=$("#homePlayers").find("tbody"),o=$("#awayPlayers").find("tbody"),(s=$("#game-form")).on("submit",N),a.on("click",j),n.on("click",w);var x=null,M=null;!h&&u&&(x=m(r,u.home),M=m(o,u.away));for(var R in y){var k=!0,S=!1,G=void 0;try{for(var E,B=y[R][Symbol.iterator]();!(k=(E=B.next()).done);k=!0){var D=E.value,F="home"===R?r:o;h?(console.log(D),F.append(f.player.format({tag:D.player_tag,position:O(D.position_id,D.position),goals:D.isGoalie?"—":D.goals,assists:D.isGoalie?"—":D.assists,id:D.player_id,stars:T(D.star)}))):g({player_id:D.player_id,position_id:D.position_id,goals:D.goals,assists:D.assists,star:D.star},D.id,"home"===R?x:M)}}catch(t){S=!0,G=t}finally{try{k||null==B.return||B.return()}finally{if(S)throw G}}}}};function m(t,e){var a="";e.forEach((function(t){a+='<option value="'.concat(t.id,'">').concat(t.tag,"</option>")}));var n=$(f.playerForm.format({id:"",player:'<select class="form-control" name="player_id">'.concat(a,"</select>"),position:y(),stars:v(),goals:"",assists:"",button:'<button class="btn btn-primary" type="submit"><i class="fas fa-user-plus"></i></button>',style:"border-top: 3px solid red;"}));return n.find("button").on("click",b),t.append(n),n}function y(t){console.log(t);var e="";return p.forEach((function(a){var n=t===a.id?"selected":"";e+='<option value="'.concat(a.id,'" ').concat(n,">").concat(a.short_title,"</option>")})),'<select class="form-control" name="position_id">'.concat(e,"</select>")}function v(t){for(var e=["—","1","2","3"],a="",n=0;n<e.length;n+=1){var r=t===n?"selected":"";a+='<option value="'.concat(n,'" ').concat(r,">").concat(e[n],"</option>")}return'<select class="form-control" name="star">'.concat(a,"</select>")}function b(t){t.preventDefault();var e=$(this).closest("tr"),a={game_id:d,team_id:+e.closest("table").data("id"),player_id:+e.find("select[name=player_id]").val(),position_id:+e.find("select[name=position_id]").val(),goals:+e.find("input[name=goals]").val(),assists:+e.find("input[name=assists]").val(),star:+e.find("select[name=star]").val()};a.isGoalie=0===a.position_id?1:0,TRNMNT_helpers.disableButtons(),$.ajax({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},type:"put",url:i.protocol,dataType:"json",contentType:"json",processData:!1,data:JSON.stringify(a),success:function(t){TRNMNT_helpers.enableButtons(),g(a,t.data.id,e),e.find("select[name=position_id]").val("0"),e.find("input[name=goals]").val(""),e.find("input[name=assists]").val(""),e.find("select[name=star]").val("0")},error:TRNMNT_helpers.onErrorAjax,context:TRNMNT_helpers})}function g(t,e,a){var n=a.find("select[name=player_id] option[value="+t.player_id+"]"),r=$(f.playerForm.format({id:e,player:n.text(),position:y(t.position_id),goals:null!==t.goals?t.goals:"",assists:null!==t.assists?t.assists:"",stars:v(t.star),button:'<button class="btn btn-primary"><i class="fas fa-edit"></i></button> <button class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>'}));a.closest("tbody").prepend(r),r.find("button.btn-primary").on("click",_),r.find("button.btn-danger").on("click",h),n.remove(),a.find("select[name=player_id] option").length||a.hide()}function h(t){if(t.preventDefault(),confirm("Удалить протокол")){var e=$(this).closest("tr"),a=$(e.find("td")[0]).text();for(var n in u){var r=!0,o=!1,s=void 0;try{for(var l,c=u[n][Symbol.iterator]();!(r=(l=c.next()).done);r=!0){var d=l.value;if(a===d.tag){var p=e.closest("table").find("select[name=player_id]");p.append('<option value="'.concat(d.id,'">').concat(d.tag,"</option>")),p.closest("tr").show()}}}catch(t){o=!0,s=t}finally{try{r||null==c.return||c.return()}finally{if(o)throw s}}}e.remove(),TRNMNT_helpers.disableButtons(),$.ajax({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},type:"delete",url:i.protocol+"/"+e.data("id"),dataType:"json",contentType:"json",processData:!1,success:function(t){TRNMNT_helpers.enableButtons()},error:TRNMNT_helpers.onErrorAjax,context:TRNMNT_helpers})}}function _(t){t.preventDefault();var e=$(this).closest("tr"),a={position_id:+e.find("select[name=position_id]").val(),goals:+e.find("input[name=goals]").val(),assists:+e.find("input[name=assists]").val(),star:+e.find("select[name=star]").val()};a.isGoalie=0===a.position_id?1:0,TRNMNT_helpers.disableButtons(),$.ajax({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},type:"post",url:i.protocol+"/"+e.data("id"),dataType:"json",contentType:"json",data:JSON.stringify(a),processData:!1,success:function(t){TRNMNT_helpers.enableButtons()},error:TRNMNT_helpers.onErrorAjax,context:TRNMNT_helpers})}function T(t){for(var e="",a=0;a<t;a+=1)e+='<i class="fas fa-star text-danger"></i>';return e}function N(t){t.preventDefault(),TRNMNT_helpers.disableButtons(),console.log(l),$.ajax({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},type:c&&!d?"put":"post",url:i.saveGame,dataType:"json",contentType:"json",processData:!1,data:l?x():M(),success:R,error:TRNMNT_helpers.onErrorAjax,context:TRNMNT_helpers})}function x(){for(var t in l.game){var e=$("#".concat(t));if(!1===e.prop("readonly")){var a=e.val();-1!==t.indexOf("_percent")&&(a=a?parseFloat(a.replace(",",".")):0),l.game[t]=a}}var n={};for(var s in r.find("select").each(m),o.find("select").each(m),l.players){var i=!0,c=!1,d=void 0;try{for(var p,u=l.players[s][Symbol.iterator]();!(i=(p=u.next()).done);i=!0){var f=p.value;f.star=n[f.player_id]}}catch(t){c=!0,d=t}finally{try{i||null==u.return||u.return()}finally{if(c)throw d}}}return JSON.stringify(l);function m(t,e){var a=$(e),r=a.closest("tr").data("id");n[r]=+a.val()}}function M(){for(var t=s.serializeArray(),e={game:{}},a=0;a<t.length;a+=1)if(t[a].value){var n=t[a].value;-1!==t[a].name.indexOf("_percent")&&(n=parseFloat(n.replace(",","."))),e.game[t[a].name]=n}return s.find("input[type=checkbox]").each((function(t,a){e.game[a.id]=+$(a).prop("checked")})),JSON.stringify(e)}function R(t){t.data.id?window.location.href=window.location.href.replace("add",t.data.id)+"/edit":(TRNMNT_helpers.enableButtons(),TRNMNT_helpers.showNotification(t.message),l&&(l=null))}function j(){TRNMNT_helpers.disableButtons(),e.empty(),$.ajax({url:i.lastGames,success:k,error:TRNMNT_helpers.onErrorAjax,context:TRNMNT_helpers})}function w(){confirm("На самом деле хотите обнулить протокол?")&&(s.find("input").each((function(t,e){var a=$(e);-1!==["checkbox","radio"].indexOf(a.attr("type"))?a.prop("checked",!1):-1!==["submit"].indexOf(a.attr("type"))||(a.val(""),"playedAt"!==e.id&&a.prop("readonly",!1))})),l?l=null:(TRNMNT_helpers.disableButtons(),$.ajax({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},type:"post",url:i.resetGame,dataType:"json",contentType:"json",processData:!1,success:R,error:TRNMNT_helpers.onErrorAjax,context:TRNMNT_helpers})),r.empty(),o.empty(),n.addClass("d-none"),e.find("button").prop("disabled",!1))}function k(t){TRNMNT_helpers.enableButtons();var a=$('<table class="table table-sm table-striped mt-3"/>');e.append(a);var s=$("<tbody/>");a.append(s);var i=function(a){var i=t.data[a].game,c=new Date(i.playedAt),d=$(f.game.format({date:c.getShortDate(),home_team:i.home_team,away_team:i.away_team,home_score:i.home_score,away_score:i.away_score}));d.find("button").click((function(){n.removeClass("d-none"),e.find("button").prop("disabled",!1),$(this).prop("disabled",!0),function(t){for(var e in(l=t).game){var a=$("#".concat(e));-1!==["checkbox","radio"].indexOf(a.attr("type"))?a.prop("checked",!!l.game[e]):a.val(l.game[e]),""!==l.game[e]&&a.prop("readonly",!0)}!function(t){for(var e in r.empty(),o.empty(),t){var a="home"===e?r:o,n=!0,s=!1,l=void 0;try{for(var i,c=t[e][Symbol.iterator]();!(n=(i=c.next()).done);n=!0){var d=i.value;a.append(f.player.format({tag:d.name,position:O(d.position_id,d.position),goals:d.goals,assists:d.assists,id:d.player_id,stars:v()}))}}catch(t){s=!0,l=t}finally{try{n||null==c.return||c.return()}finally{if(s)throw l}}}}(l.players)}(t.data[a])})),s.append(d)};for(var c in t.data)i(c)}function O(t,e){var a="";switch(t){case 0:a="badge-goalie";break;case 1:a="badge-defender";break;case 3:a="badge-left_wing";break;case 4:a="badge-center";break;case 5:a="badge-right_wing"}return'<span class="badge '.concat(a,'">').concat(e.short_title,"</span>")}}()}});