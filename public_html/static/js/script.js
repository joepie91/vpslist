/* Array.filter polyfill 
 * Source: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/filter 
 * Modifications: reindented for consistency */
if (!Array.prototype.filter)
{
	Array.prototype.filter = function(fun /*, thisArg */)
	{
		"use strict";

		if (this === void 0 || this === null)
			throw new TypeError();

		var t = Object(this);
		var len = t.length >>> 0;
		if (typeof fun != "function")
			throw new TypeError();

		var res = [];
		var thisArg = arguments.length >= 2 ? arguments[1] : void 0;
		for (var i = 0; i < len; i++)
		{
			if (i in t)
			{
				var val = t[i];

				// NOTE: Technically this should Object.defineProperty at
				//       the next index, as push can be affected by
				//       properties on Object.prototype and Array.prototype.
				//       But that method's new, and collisions should be
				//       rare, so use the more-compatible alternative.
				if (fun.call(thisArg, val, i, t))
					res.push(val);
			}
		}

		return res;
	};
}

/* Array.some polyfill
 * Source: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/some
 * Modifications: reindented fo00r consistency */
if (!Array.prototype.some)
{
	Array.prototype.some = function(fun /*, thisArg */)
	{
		'use strict';

		if (this === void 0 || this === null)
			throw new TypeError();

		var t = Object(this);
		var len = t.length >>> 0;
		if (typeof fun !== 'function')
			throw new TypeError();

		var thisArg = arguments.length >= 2 ? arguments[1] : void 0;
		for (var i = 0; i < len; i++)
		{
			if (i in t && fun.call(thisArg, t[i], i, t))
				return true;
		}

		return false;
	};
}

/* Global utility functions */
util = {
	objectToArray: function(object) {
		var arr = [];
		for(key in object)
		{
			arr.push({
				key: key,
				value: object[key]
			});
		}
		return arr;
	}
}

/* Module code */
var module = angular.module("vpslist", []);

module.controller("appController", function($scope){
	$scope.filters = {};
	$scope.sources = {
		countries: {
			"NL": "Netherlands (NL)",
			"UK": "United Kingdom (UK)",
			"FR": "France (FR)",
			"DE": "Germany (DE)",
			"US": "United States (US)",
			"CA": "Canada (CA)",
			"LU": "Luxembourg (LU)"
		},
		facilities: {
			"1": "ColoCrossing Buffalo",
			"2": "ColoCrossing Atlanta",
			"3": "Choopa",
			"4": "Colo@ Atlanta",
			"5": "OVH",
			"6": "Hetzner",
			"7": "PlusServer",
			"8": "WholeSale Internet",
			"9": "Leaseweb"
		},
		providers: {
			"1": "RAM Host",
			"2": "VPS-Forge",
			"3": "ErrantWeb",
			"4": "IPXCore",
			"5": "Leaseweb",
		}
	}
});

module.directive("filterSection", function(){
	return {
		restrict: "E",
		templateUrl: "templates/angular/directives/filter-section.html",
		transclude: true,
		scope: {
			title: "@",
			visible: "=ngModel"
		},
		link: function(scope, element, attrs) {
			scope.visible = angular.isDefined(attrs.visible) ? true : false;
		}
	}
});

module.directive("buttonGroup", function(){
	return {
		restrict: "E",
		templateUrl: "templates/angular/directives/button-group.html",
		transclude: true,
		scope: {
			title: "@"
		},
		link: function(scope, element, attrs) {
			
		}
	}
});

module.directive("buttonItem", function(){
	return {
		restrict: "E",
		templateUrl: "templates/angular/directives/button-item.html",
		transclude: true,
		replace: true,
		scope: {
			title: "@",
			selected: "=ngModel"
		},
		link: function(scope, element, attrs) {
			scope.selected = angular.isDefined(attrs.selected) ? true : false;
		}
	}
});

module.directive("checkbox", function(){
	return {
		restrict: "E",
		templateUrl: "templates/angular/directives/button-item.html",
		transclude: true,
		replace: true,
		scope: {
			title: "@",
			selected: "=ngModel"
		},
		link: function(scope, element, attrs) {
			scope.selected = angular.isDefined(attrs.selected) ? true : false;
		}
	}
});

module.directive("searchList", function(){
	return {
		restrict: "E",
		templateUrl: "templates/angular/directives/search-list.html",
		scope: {
			ngModelType: "=?",
			ngModelItems: "=?",
			source: "="
		},
		link: function(scope, element, attrs) {
			scope.listCurrent = 0;
			scope.listVisible = false;
			scope.listQuery = "";
			scope.ngModelItems = [];
			scope.ngModelType = "include";
			
			scope.$watchCollection("[source, listQuery]", function(){
				if (scope.listQuery !== "")
				{
					scope.listItems = util.objectToArray(scope.source).filter(function(item){
						var matches = (item.key.toLowerCase().indexOf(scope.listQuery.toLowerCase()) !== -1 || item.value.toLowerCase().indexOf(scope.listQuery.toLowerCase()) !== -1);
						var selected = (scope.ngModelItems.filter(function(selected_item){ return item.key == selected_item.key; }).length > 0);
						return matches && !selected;
					}).sort(function(a, b){
						index_a = a.value.indexOf(scope.listQuery);
						
						if (index_a === -1)
						{
							index_a = a.key.indexOf(scope.listQuery);
						}
						
						index_b = b.value.indexOf(scope.listQuery);
						
						if (index_b === -1)
						{
							index_b = b.key.indexOf(scope.listQuery);
						}
						
						return index_a - index_b;
					});
				}
				else
				{
					scope.listVisible = false;
				}
			});
			
			scope.deleteItem = function(target)
			{
				/* TODO: Optimize */
				scope.ngModelItems = scope.ngModelItems.filter(function(item){
					return (item.key != target.key);
				});
			}
			
			scope.selectItem = function(target)
			{
				scope.listCurrent = scope.listItems.indexOf(target);
			}
			
			scope.chooseItem = function(target)
			{
				var item_exists = scope.ngModelItems.some(function(item){
					return (item.key == scope.listItems[scope.listCurrent].key); 
				});
				
				if (item_exists == false)
				{
					scope.ngModelItems.push(scope.listItems[scope.listCurrent]);
				}
				
				scope.listQuery = "";
				scope.listCurrent = 0;
				scope.listVisible = false;
			}
			
			element.find("input.search")
				.on("keydown.searchList", function(event){
					if (event.keyCode == 9)
					{
						/* Make the TAB key not switch away from the field before the keyup can register */
						if (scope.listVisible == true)
						{
							event.preventDefault();
						}
					}
					else if (event.keyCode == 38)
					{
						/* Move up in the list */
						if (scope.listCurrent > 0)
						{
							scope.listCurrent -= 1;
						}
						
						scope.listVisible = true;
						event.preventDefault();
					}
					else if (event.keyCode == 40)
					{
						/* Move down in the list */
						if (scope.listCurrent < (scope.listItems.length - 1))
						{
							scope.listCurrent += 1;
						}
						
						scope.listVisible = true;
						event.preventDefault();
					}
					
					scope.$apply();
				})
				.on("keyup.searchList", function(event){
					if (event.keyCode == 13 || event.keyCode == 9)
					{
						if (scope.listItems.length > 0)
						{
							/* Add currently selected item, but only if it isn't in the list yet
							 * TODO: Maybe hide existing items from the autocomplete list in the first place? */
							scope.chooseItem(scope.listItems[scope.listCurrent]);
						}
						
						event.preventDefault();
					}
					else if (event.keyCode == 27)
					{
						/* Close the list */
						scope.listVisible = false;
						event.preventDefault();
					}
					else
					{
						/* Display the list */
						if (scope.listQuery !== "")
						{
							scope.listVisible = true;
						}
						
						if (event.keyCode != 38 && event.keyCode != 40)
						{
							scope.listCurrent = 0;
						}
					}
					
					scope.$apply();
				})
				
			var parent_container = element.closest(".filter-section");
			var parent_scope = parent_container.scope();
			
			if(parent_scope.visible == false)
			{
				parent_container.children(".section-contents").removeClass("ng-hide");
			}
			
			element.find(".search-results")
				.css({
					left: element.find("input.search").position().left,
					top: element.find("input.search").position().top + element.find("input.search").outerHeight() - 1,
					width: element.find("input.search").outerWidth()
				});
			
			if(parent_scope.visible == false)
			{
				parent_container.children(".section-contents").addClass("ng-hide")
			}
		}
	}
});

module.directive("slider", function(){
	return {
		restrict: "E",
		templateUrl: "templates/angular/directives/slider.html",
		transclude: true,
		replace: true,
		scope: {
			title: "@",
			min: "@",
			max: "@",
			unit: "@",
			digits: "@",
			ngModelLow: "=?",
			ngModelHigh: "=?",
		},
		link: function(scope, element, attrs) {
			/* Visibility hack to be able to grab the outerWidth */
			var parent_container = element.closest(".filter-section");
			var parent_scope = parent_container.scope();
			
			if(parent_scope.visible == false)
			{
				parent_container.children(".section-contents").removeClass("ng-hide");
			}
			
			scope.maxPx = element.find(".bar .outline").outerWidth() - element.find(".handle-right").outerWidth();
			
			if(parent_scope.visible == false)
			{
				parent_container.children(".section-contents").addClass("ng-hide")
			}
			
			/* Nothing to see here, carry on... */
			scope.posLeft = 0;
			scope.posRight = scope.maxPx;
			
			function updateFill()
			{
				var left = scope.posLeft + element.find(".handle-left").outerWidth();
				var width = scope.posRight - left;
				element.find(".bar .fill").css({left: left, width: width});
			}
			
			function isNumber(n)
			{
				return (!isNaN(parseFloat(n)) && isFinite(n));
			}
			
			scope.$watch("posLeft", function(){
				element.find(".handle-left").css({left: scope.posLeft});
				updateFill();
				scope.ngModelLow = Math.round(parseInt(scope.max) * (scope.posLeft / scope.maxPx));
			});
			
			scope.$watch("posRight", function(){
				element.find(".handle-right").css({left: scope.posRight});
				updateFill();
				scope.ngModelHigh = Math.round(parseInt(scope.max) * (scope.posRight / scope.maxPx));
			});
			
			scope.$watch("ngModelLow", function(){
				if(isNumber(scope.ngModelLow) == false)
				{
					return; /* ignore */
				}
				
				if(scope.ngModelLow < scope.min)
				{
					scope.ngModelLow = scope.min;
				}
				else if (scope.ngModelLow > scope.max)
				{
					scope.ngModelLow = scope.max;
				}
				else if (scope.ngModelLow > scope.ngModelHigh)
				{
					scope.ngModelLow = scope.ngModelHigh;
				}
				
				scope.posLeft = scope.maxPx * (scope.ngModelLow / scope.max);
			});
			
			scope.$watch("ngModelHigh", function(){
				if(isNumber(scope.ngModelHigh) == false)
				{
					return; /* ignore */
				}
				
				if(scope.ngModelHigh < scope.min)
				{
					scope.ngModelHigh = scope.min;
				}
				else if (scope.ngModelHigh > scope.max)
				{
					scope.ngModelHigh = scope.max;
				}
				else if (scope.ngModelHigh < scope.ngModelLow)
				{
					scope.ngModelHigh = scope.ngModelLow;
				}
				
				scope.posRight = scope.maxPx * (scope.ngModelHigh / scope.max);
			});
			
			element.find(".handle-right")
				.css({left: scope.maxPx});
				
			element.find(".handle")
				.mousedown(function(event){
					/* Clear any events that might be remaining from a previous drag... */
					$("body").off("mouseup.sliderDrag");
					$("body").off("mousemove.sliderDrag");
					
					var offsetX = event.pageX;
					var startX = $(this).position().left;
					
					scope.sliding = true;
					scope.sliding_offset = offsetX;
					scope.sliding_start = startX;
					
					var this_element = this;
					
					$("body")
						.on("mouseup.sliderDrag", function(event){
							scope.sliding = false;
							$("body").off("mouseup.sliderDrag");
							$("body").off("mousemove.sliderDrag");
						})
						.on("mousemove.sliderDrag", function(event){
							if (scope.sliding == true)
							{
								var mouseX = event.pageX - scope.sliding_offset;
								var newX = scope.sliding_start + mouseX;
								
								if (newX > scope.maxPx)
								{
									newX = scope.maxPx;
								}
								else if (newX < 0)
								{
									newX = 0;
								}
								
								if ($(this_element).hasClass("handle-right"))
								{
									scope.posRight = newX;
								}
								else if ($(this_element).hasClass("handle-left"))
								{
									scope.posLeft = newX;
								}
								
								scope.$apply();
							}
						});
						
					event.preventDefault();
					event.stopPropagation();
				});
		}
	}
});

module.directive("ngModelBlur", function(){
	return {
		restrict: "A",
		scope: {
			value: "=ngModelBlur"
		},
		link: function(scope, element, attrs){
			scope.dirty = false;
			
			scope.$watch("value", function(){
				element.val(scope.value);
				scope.dirty = true;
			});
			
			function updateValue()
			{
				scope.value = element.val();
				scope.$apply();
			}
			
			element.on("blur", function(){
				updateValue();
			});
			
			element.on("keypress", function(event){
				if (event.keyCode == 13)
				{
					if (scope.dirty == true)
					{
						scope.dirty = false;
						updateValue();
					}
				}
				else
				{
					scope.dirty = true;
				}
			});
		}
	};
});
