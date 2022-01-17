
function UEDynamicFilters(){
	
	var g_objFilters, g_objGrid, g_filtersData, g_urlBase;
	var g_urlAjax; 
	
	var g_types = {
		CHECKBOX:"checkbox"
	};
	
	var g_vars = {
		CLASS_DIV_DEBUG:"uc-div-ajax-debug"	
	};
	
	var g_options = {
			has_grid: false,
			ajax_reload: false,
			widget_name: null
	};
	
	
	/**
	 * console log some string
	 */
	function trace(str){
		console.log(str);
	}
	
	function ________GENERAL_______________(){}
	
	
	/**
	 * get object property
	 */
	function getVal(obj, name, defaultValue){
		
		if(!defaultValue)
			var defaultValue = "";
		
		var val = "";
		
		if(!obj || typeof obj != "object")
			val = defaultValue;
		else if(obj.hasOwnProperty(name) == false){
			val = defaultValue;
		}else{
			val = obj[name];			
		}
		
		return(val);
	}
	
	/**
	 * turn string value ("true", "false") to string 
	 */
	function strToBool(str){
		
		switch(typeof str){
			case "boolean":
				return(str);
			break;
			case "undefined":
				return(false);
			break;
			case "number":
				if(str == 0)
					return(false);
				else 
					return(true);
			break;
			case "string":
				str = str.toLowerCase();
						
				if(str == "true" || str == "1")
					return(true);
				else
					return(false);
				
			break;
		}
		
		return(false);
	};
	
	/**
	 * get closest grid to some object
	 */
	function getClosestGrid(objSource){
		
		if(!g_objGrid)
			return(null);
		
		if(g_objGrid.length == 0)
			return(null);
		
		
		return(g_objGrid);
	}
	
	/**
	 * add filter object to grid
	 */
	function bindFilterToGrid(objGrid, objFilter){
		
		var arrFilters = objGrid.data("filters");
		
		if(!arrFilters)
			arrFilters = [];
		
		arrFilters.push(objFilter);
		
		objGrid.data("filters", arrFilters);
		
	}
	
	
	
	function ________FILTERS_______________(){}
	
	
	/**
	 * get filter type
	 */
	function getFilterType(objFilter){
		
		if(objFilter.is(":checkbox"))
			return(g_types.CHECKBOX);
		
		return(null);
	}
	
	
	/**
	 * clear filter
	 */
	function clearFilter(objFilter){
		
		var type = getFilterType(objFilter);
		
		switch(type){
			case g_types.CHECKBOX:
				objFilter.prop("checked", false);
			break;
		}
		
	}
	
	
	/**
	 * clear filters
	 */
	function clearFilters(checkActive){
		
		jQuery.each(g_objFilters,function(index, filter){
			
			var objFilter = jQuery(filter);
			
			if(checkActive == true){
				
				var isActive = objFilter.data("active");
				if(isActive == "yes")
					return(true);
			}
			
			clearFilter(objFilter);
			
		});
		
	}
	
	/**
	 * get filter data
	 */
	function getFilterData(objFilter){
		
		var objData = {};
		
		var type = getFilterType(objFilter);
		
		var type = objFilter.data("type");
		
		objData["type"] = type;
		
		switch(type){
			case "term":
				var taxonomy = objFilter.data("taxonomy");
				var term = objFilter.data("term");
				
				objData["taxonomy"] = taxonomy;
				objData["term"] = term;
			break;
			default:
				throw new Error("getFilterData error: wrong data type: "+type);
			break;
		}
		
		return(objData);
	}
	
	/**
	 * check if the filter selected
	 */
	function isFilterSelected(objFilter){
		
		var type = getFilterType(objFilter);
		
		switch(type){
			case g_types.CHECKBOX:
				
				var isSelected = objFilter.is(":checked");
				
				return(isSelected);
				
			break;
			default:
				throw new Error("isFilterSelected error. wrong type: "+type);
			break;
		}
		
		
		return(false);
	}
	
	
	/**
	 * get all selected filters
	 */
	function getSelectedFilters(){
		
		var objSelected = [];
		
		jQuery.each(g_objFilters, function(index, filter){
			
			var objFilter = jQuery(filter);
			
			var isSelected = isFilterSelected(objFilter);
			
			if(isSelected == true)
				objSelected.push(objFilter);
			
		});
		
		
		return(objSelected);
	}
	
	function ________PAGINATION_FILTER______(){}
	
	/**
	 * check if the filter is pagination
	 */
	function isPaginationFilter(objFilter){
		
		if(objFilter.hasClass("uc-filter-pagination"))
			return(true);
		
		return(false);
	}
	
	/**
	 * get pagination selected url or null if is current
	 */
	function getPaginationSelectedUrl(objPagination){
		
		var objCurrentLink = objPagination.find("a.current");
		
		if(objCurrentLink.length == 0)
			return(null);
		
		var url = objCurrentLink.attr("href");
		
		if(!url)
			return(null);
		
		return(url);
	}
	
	function ________DATA_______________(){}
	
	 
	/**
	 * get filters data array
	 */
	function getArrFilterData(){
		
		var objFilters = getSelectedFilters();
		
		if(objFilters.length == 0)
			return([]);
		
		var arrData = [];
		
		jQuery.each(objFilters, function(index, filter){
			
			var objFilter = jQuery(filter);
			
			var objFilterData = getFilterData(objFilter);
			
			arrData.push(objFilterData);
		});
		
		return(arrData);
	}
	
	/**
	 * consolidate filters data
	 */
	function consolidateFiltersData(arrData){
		
		if(arrData.length == 0)
			return([]);
		
		//consolidate by taxonomies
		
		var objTax = {};
		
		jQuery.each(arrData, function(index, item){
			
			switch(item.type){
				case "term":
					
					var taxonomy = item.taxonomy;
					var term = item.term;
					
					if(objTax.hasOwnProperty(taxonomy) == false)
						objTax[taxonomy] = [];
					
					objTax[taxonomy].push(term);
					
				break;
				default:
					throw new Error("consolidateFiltersData error: wrong type: "+item.type);
				break;
			}
			
		});
		
		var arrConsolidated = {};
		arrConsolidated["terms"] = objTax;
		
		return(arrConsolidated);
	}
	
	/**
	 * build terms query
	 */
	function buildQuery_terms(objTax){
		
		var query = "";
		
		jQuery.each(objTax, function(taxonomy, arrTerms){
			
			var strTerms = arrTerms.join(".");
			if(!strTerms)
				return(true);

			//separator
			
			if(query)
				taxonomy += ";";
			
			//query
			
			query += taxonomy + "~" + strTerms;
		});
		
		return(query);
	}
	
	
	/**
	 * build url query from the filters
	 * example:
	 * ucfilters=product_cat~shoes,dress;cat~123,43;
	 */
	function buildUrlQuery(){
				
		var arrData = getArrFilterData();
		
		if(arrData.length == 0)
			return("");
		
		var queryFilters = "";
		
		var arrConsolidated = consolidateFiltersData(arrData);
		
		jQuery.each(arrConsolidated, function(type, objItem){
			
			switch(type){
				case "terms":
					var queryTerms = buildQuery_terms(objItem);
					
					if(queryFilters)
						queryFilters += ";";
					
					queryFilters += queryTerms;
				break;
			}
			
		});
		
		//return query
		
		var query = "ucfilters=" + queryFilters;
		
		return(query);
	}
	
	/**
	 * get redirect url
	 */
	function getRedirectUrl(query){
		
		if(!g_urlBase)
			throw new Error("getRedirectUrl error - empty url");
		
		var url = g_urlBase;
		
		if(!query)
			return(url);
		
		var posQ = url.indexOf("?");
		
		if(posQ == -1)
			url += "?";
		else
			url += "&";
		
		url += query;
		
		return(url);
	}

	function ________AJAX_______________(){}
	
	/**
	 * show ajax error, should be something visible
	 */
	function showAjaxError(message){
		
		alert(message);
		
	}
	
	/**
	 * get the debug object
	 */
	function getDebugObject(){
				
		var objDebug = g_objGrid.find("."+g_vars.CLASS_DIV_DEBUG);
		
		if(objDebug.length)
			return(objDebug);
		
		//insert if not exists
		
		g_objGrid.after("<div class='"+g_vars.CLASS_DIV_DEBUG+"' style='padding:10px;display:none;background-color:lightgray'></div>");
		
		var objDebug = jQuery("body").find("."+g_vars.CLASS_DIV_DEBUG);
		
		return(objDebug);
	}
	
	
	/**
	 * show ajax debug
	 */
	function showAjaxDebug(str){
		
		var objDebug = getDebugObject();
		
		if(objDebug.length == 0){
			throw new Error("debug not found");
		}
		
		objDebug.show();
		objDebug.html(str);
		
	}
	
	
	/**
	 * small ajax request
	 */
	function ajaxRequest(ajaxUrl, action, objData, onSuccess){
				
		if(!objData)
			var objData = {};
		
		if(typeof objData != "object")
			throw new Error("wrong ajax param");
				
		var ajaxData = {};
		ajaxData["action"] = "unlimitedelements_ajax_action";
		ajaxData["client_action"] = action;
		
		var ajaxtype = "get";
		
		if(objData){
			ajaxData["data"] = objData;
			ajaxtype = "post";
		}
			
		var ajaxOptions = {
				type:ajaxtype,
				url:ajaxUrl,
				success:function(response){
					
					if(!response){
						showAjaxError("Empty ajax response!");
						return(false);					
					}
										
					if(typeof response != "object"){
						
						try{
							
							response = jQuery.parseJSON(response);
							
						}catch(e){
							
							showAjaxDebug(response);
							
							showAjaxError("Ajax Error!!! not ajax response");
							return(false);
						}
					}
					
					if(response == -1){
						showAjaxError("ajax error!!!");
						return(false);
					}
					
					if(response == 0){
						showAjaxError("ajax error, action: <b>"+action+"</b> not found");
						return(false);
					}
					
					if(response.success == undefined){
						showAjaxError("The 'success' param is a must!");
						return(false);
					}
					
					
					if(response.success == false){
						showAjaxError(response.message);
						return(false);
					}
					
					if(typeof onSuccess == "function")
						onSuccess(response);
					
				},
				error:function(jqXHR, textStatus, errorThrown){
										
					switch(textStatus){
						case "parsererror":
						case "error":
							
							//showAjaxError("parse error");
							
							showAjaxDebug(jqXHR.responseText);
							
						break;
					}
				}
		}
		
		if(ajaxtype == "post"){
			ajaxOptions.dataType = 'json';
			ajaxOptions.data = ajaxData
		}
		
		jQuery.ajax(ajaxOptions);
		
	}
	
	
	/**
	 * on ajax pagination click
	 */
	function onAjaxPaginationLinkClick(event){
		
		var objLink = jQuery(this);
		
		var objNav = objLink.parents("nav");
			
		var objLinkCurrent = objNav.find(".current");
		
		objLinkCurrent.removeClass("current");
		
		objLink.addClass("current");
		
		var objPagination = objLink.parents(".uc-filter-pagination");
		
		var objGrid = objPagination.data("grid");
		
		if(!objGrid || objGrid.length == 0)
			throw new Error("Grid not found!");
		
		//run the ajax, prevent default
		
		refreshAjaxGrid(objGrid);
		
		event.preventDefault();
		return(false);
	}
	
	/**
	 * operate the response
	 */
	function operateAjaxRefreshResponse(response){
		
		alert("ajax response success!!!");
		
		trace(response);
		
	}
	
	/**
	 * refresh ajax grid
	 */
	function refreshAjaxGrid(objGrid){
		
		//get all grid filters
		var objFilters = objGrid.data("filters");
		
		if(!objFilters)
			return(false);
		
		if(objFilters.length == 0)
			return(false);
		
		var objAjaxOptions = getGridAjaxOptions(objFilters);
		
		if(!objAjaxOptions)
			return(false);
		
		var ajaxUrl = objAjaxOptions["ajax_url"];
		
		ajaxRequest(ajaxUrl,null,null, operateAjaxRefreshResponse);
	}
	
	
	function ________RUN_______________(){}
	
	/**
	 * on filters change - refresh the page with the new query
	 */
	function onFiltersChange(objGrid){
		
		var query = buildUrlQuery();
				
		var url = getRedirectUrl(query);
		
		if(!url)
			throw new error("onFiltersChange error - empty redirect url");
				
		location.href = url;
	}
	
	
	
	/**
	 * get element layout data
	 */
	function getElementLayoutData(objElement){
		
		if(!objElement || objElement.length == 0)
			throw new Error("Element not found");
		
		//get widget id
		
		var objWidget = objElement.parents(".elementor-widget");
		
		if(objWidget.langth == 0)
			throw new Error("Element parent not found");
		
		var widgetID = objWidget.data("id");
		
		if(!widgetID)
			throw new Error("widget id not found");
			
		//get layout id
		var objLayout = objWidget.parents(".elementor");
		
		if(objLayout.length == 0)
			throw new Error("layout not found");
		
		var layoutID = objLayout.data("elementor-id");
		
		var output = {};
		
		output["widgetid"] = widgetID;
		output["layoutid"] = layoutID;
		
		return(output);
	}
	
	
	/**
	 * get grid ajax options
	 */
	function getGridAjaxOptions(objFilters){
		
		if(!objFilters)
			return(false);
		
		var urlAjax = g_urlBase;
		
		//get ajax options
		jQuery.each(objFilters, function(index, objFilter){
			
			var isPagination = isPaginationFilter(objFilter);
			
			if(isPagination == true){
				var urlPagination = getPaginationSelectedUrl(objFilter);
				if(urlPagination)
					urlAjax = urlPagination;
			}
			
		});
		
		var dataLayout = getElementLayoutData(g_objGrid);
		
		var widgetID = dataLayout["widgetid"];
		var layoutID = dataLayout["layoutid"];
		
		urlAjax += "?ucfrontajaxaction=getfiltersdata&layoutid="+layoutID+"&elid="+widgetID;
		
		var output = {};
		output["ajax_url"] = urlAjax;
		
		return(output);
	}
	
	
	
	function ________INIT_______________(){}
	
		
	
	/**
	 * init events
	 */
	function initEvents(){
		
		var objCheckboxes = g_objFilters.filter("input[type=checkbox]");
		
		objCheckboxes.on("click", onFiltersChange);
		
	}
	
	/**
	 * init listing object
	 */
	function initGridObject(){
				
		//init the listing
		g_objGrid = jQuery(".uc-filterable-grid");
		
		if(g_objGrid.length == 0){
			g_objGrid = null;
			return(false);
		}
		
		//set the options
		
		//get first grid
		if(g_objGrid.length > 1){
			g_objGrid = jQuery(g_objListing[0]);
		}
		
		g_options.has_grid = true;
		
		//init options
		//var isAjax = g_objGrid.data("ajax");
				
	}
	
	
	/**
	 * init grid filters
	 */
	function initGridFilters(){
		
		//init the filters objects
		g_objFilters = jQuery(".uc-grid-filter");
		
		if(g_objFilters.length == 0){
			return(false);
		}
		
	}
		
	
	/**
	 * init the globals
	 */
	function initGlobals(){
		
		if(typeof g_strFiltersData != "undefined"){
			g_filtersData = JSON.parse(g_strFiltersData);
		}
		
		if(jQuery.isEmptyObject(g_filtersData)){
			
			trace("filters error - filters data not found");
			return(false);
		}
		
		g_urlBase = getVal(g_filtersData, "urlbase");
		g_urlAjax = getVal(g_filtersData, "urlajax");
		
		if(!g_urlBase){
			trace("ue filters error - base url not inited");
			return(false);
		}

		if(!g_urlAjax){
			trace("ue filters error - ajax url not inited");
			return(false);
		}
		
		return(true);
	}
	
	
	
	/**
	 * init pagination filter
	 */
	function initPaginationFilter(){
		
		var objPagination = jQuery(".uc-filter-pagination");
		
		if(objPagination.length == 0)
			return(false);
		
		var objGrid = getClosestGrid(objPagination);
		
		if(!objGrid)
			return(null);
		
		var isAjax = objGrid.data("ajax");
		
		if(isAjax == false)
			return(false);
		
		//init the ajax pagination
		
		//bind grid to pagination
		objPagination.data("grid", objGrid);
		
		//bind pagination to grid
		bindFilterToGrid(objGrid, objPagination);
		
		var objLinks = objPagination.find("a");
		objLinks.on("click", onAjaxPaginationLinkClick);
		
	}
	
	
	
	/**
	 * init
	 */
	function init(){
				
		var success = initGlobals();
		
		if(success == false)
			return(false);
		
		//init the grid object
		initGridObject();
		
		//initGridFilters();
		
		initPaginationFilter();
		
		//clearFilters(true);
		//initEvents();
		
	}
	
	
	/**
	 * init the class
	 */
	function construct(){
		
		if(!jQuery){
			trace("Filters not loaded, jQuery not loaded");
			return(false);
		}
				
		jQuery("document").ready(init);
		
	}
	
	construct();
}

new UEDynamicFilters();

