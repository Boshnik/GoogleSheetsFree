GoogleSheets.grid.Export=function(e){(e=e||{}).id||(e.id="gs-grid-export"),Ext.applyIf(e,{baseParams:{action:"mgr/export/getlist",sort:"menuindex",dir:"asc"},ddAction:"mgr/export/sort",multi_select:!1}),GoogleSheets.grid.Export.superclass.constructor.call(this,e)},Ext.extend(GoogleSheets.grid.Export,GoogleSheets.grid.Default,{windows:{},getFields:function(){return["id","desc","range","model_class","where","published","spreadsheet","actions"]},getColumns:function(){return[{header:_("gs_field_id"),dataIndex:"id",sortable:!0,width:75,fixed:!0},{header:_("gs_field_desc"),dataIndex:"desc",sortable:!1,width:"auto"},{header:_("gs_field_range"),dataIndex:"range",sortable:!1,width:"auto"},{header:_("gs_field_model_class"),dataIndex:"model_class",sortable:!1,width:"auto"},{header:_("gs_field_where"),dataIndex:"where",sortable:!1,width:"auto"},{header:_("gs_grid_actions"),dataIndex:"actions",renderer:GoogleSheets.utils.renderActions,sortable:!1,width:220,fixed:!0,id:"actions",hidden:"2"!==GoogleSheets.config.modxversion}]},exportObject:function(){if("undefined"!=typeof row)this.menu.record=row.data;else if(!this.menu.record)return!1;var t=new Ext.LoadMask(Ext.getBody(),{msg:"Please wait..."});t.show(),MODx.msg.confirm({title:_("gs_export"),text:_("gs_export_confirm"),url:this.config.url,params:{action:"mgr/export/export",id:this.menu.record.id},listeners:{success:{scope:this,fn:function(e){t.hide(),MODx.msg.alert("Success",e.message),this.refresh()}},failure:{fn:function(e){t.hide(),MODx.msg.alert("Warning!",e.message||"Error",function(){}),this.refresh()},scope:this},cancel:{fn:function(){t.hide()}}}})}}),Ext.reg("gs-grid-export",GoogleSheets.grid.Export);