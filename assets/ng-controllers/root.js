var myApp = angular.module("myApp", ["ngRoute", "ngSanitize", "oc.lazyLoad"]);

myApp.config(function ($routeProvider, $httpProvider) {
  $httpProvider.interceptors.push("sessionInterceptor");

  var cacheVersion = new Date().getTime(); // Generates a unique timestamp
  var dynamicRouteData = [
    //#region Dashboard
    {
      path: "/dashboard",
      templateUrl: "dashboard",
      controller: "dashboard-controller",
      title: "Dashboard",
      script: ["assets/ng-controllers/dashboard.js"],
    },
    //#region Masterfiles
    {
      path: "/customer",
      templateUrl: "customer",
      controller: "customer-controller",
      title: "Customer",
      script: ["assets/ng-controllers/customer.js"],
    },
    {
      path: "/town",
      templateUrl: "town",
      controller: "town-controller",
      title: "Town",
      script: ["assets/ng-controllers/town.js"],
    },
    {
      path: "/subsidiary",
      templateUrl: "subsidiary",
      controller: "subsidiary-controller",
      title: "Subsidiary",
      script: ["assets/ng-controllers/subsidiary.js"],
    },
    {
      path: "/supplier",
      templateUrl: "supplier",
      controller: "supplier-controller",
      title: "Supplier",
      script: ["assets/ng-controllers/supplier.js"],
    },
    {
      path: "/item",
      templateUrl: "item",
      controller: "item-controller",
      title: "Item",
      script: ["assets/ng-controllers/item.js"],
    },
    {
      path: "/location",
      templateUrl: "location",
      controller: "location-controller",
      title: "Location",
      script: ["assets/ng-controllers/location.js"],
    },
    {
      path: "/truck",
      templateUrl: "truck",
      controller: "truck-controller",
      title: "Truck",
      script: ["assets/ng-controllers/truck.js"],
    },
    {
      path: "/vehicle",
      templateUrl: "vehicle",
      controller: "vehicle-controller",
      title: "Vehicle",
      script: ["assets/ng-controllers/vehicle.js"],
    },
    {
      path: "/driver",
      templateUrl: "driver",
      controller: "driver-controller",
      title: "Driver",
      script: ["assets/ng-controllers/driver.js"],
    },
    {
      path: "/convoy",
      templateUrl: "convoy",
      controller: "convoy-controller",
      title: "Convoy",
      script: ["assets/ng-controllers/convoy.js"],
    },
    {
      path: "/division",
      templateUrl: "division",
      controller: "division-controller",
      title: "Division",
      script: ["assets/ng-controllers/division.js"],
    },
    {
      path: "/category",
      templateUrl: "category",
      controller: "category-controller",
      title: "Category",
      script: ["assets/ng-controllers/category.js"],
    },
    {
      path: "/group",
      templateUrl: "group",
      controller: "group-controller",
      title: "Group",
      script: ["assets/ng-controllers/group.js"],
    },
    {
      path: "/class",
      templateUrl: "class",
      controller: "class-controller",
      title: "Class",
      script: ["assets/ng-controllers/class.js"],
    },
    {
      path: "/checkers",
      templateUrl: "checkers",
      controller: "checkers-controller",
      title: "Checkers",
      script: ["assets/ng-controllers/checkers.js"],
    },
    {
      path: "/standard-km-masterfile",
      templateUrl: "standard-km-masterfile",
      controller: "standard-km-masterfile-controller",
      title: "Standard KM Masterfile",
      script: ["assets/ng-controllers/standard-km-masterfile.js"],
    },
    {
      path: "/fad-path",
      templateUrl: "fad-path",
      controller: "fad-path-controller",
      title: "FAD Path",
      script: ["assets/ng-controllers/fad-path.js"],
    },
    {
      path: "/adjustment-reason",
      templateUrl: "adjustment-reason",
      controller: "adjustment-reason-controller",
      title: "Adjustment Reason",
      script: ["assets/ng-controllers/adjustment-reason.js"],
    },
    {
      path: "/other-charges",
      templateUrl: "other-charges",
      controller: "other-charges-controller",
      title: "Other Charges",
      script: ["assets/ng-controllers/other-charges.js"],
    },
    {
      path: "/price-group",
      templateUrl: "price-group",
      controller: "price-group-controller",
      title: "Price Group",
      script: ["assets/ng-controllers/price-group.js"],
    },
    {
      path: "/posting-setup",
      templateUrl: "posting-setup",
      controller: "posting-setup-controller",
      title: "Posting Setup",
      script: ["assets/ng-controllers/posting-setup.js"],
    },
    {
      path: "/user",
      templateUrl: "user",
      controller: "user-controller",
      title: "User",
      script: ["assets/ng-controllers/user.js"],
    },
    //#endregion Masterfiles
  ];

  //#region dynamicRouteData V3
  dynamicRouteData.forEach(function (route) {
    $routeProvider.when(route.path, {
      templateUrl: route.templateUrl,
      title: route.title,
      controller: route.controller || null,
      resolve: {
        loadAssets: [
          "$ocLazyLoad",
          function ($ocLazyLoad) {
            let assets = [];

            const addAsset = (files) => {
              if (!files) return;
              (Array.isArray(files) ? files : [files]).forEach((file) => {
                assets.push(`${window.$base_url}${file}?v=${cacheVersion}`);
              });
            };

            addAsset(route.css);
            addAsset(route.script);

            return assets.length
              ? $ocLazyLoad.load({ name: "myApp", files: assets })
              : Promise.resolve();
          },
        ],
      },
    });
  });

  // Optionally, you can specify a default route if none of the dynamic routes match
  $routeProvider.otherwise({
    redirectTo: "/dashboard",
  });

  // Add Pace interceptor
  $httpProvider.interceptors.push([
    "$q",
    function ($q) {
      return {
        request: function (config) {
          Pace.restart();
          return config;
        },
        response: function (response) {
          Pace.stop();
          return response;
        },
        responseError: function (rejection) {
          Pace.stop();
          return $q.reject(rejection);
        },
      };
    },
  ]);
});

//#region sessionInterceptor
myApp.factory("sessionInterceptor", function ($q, $injector) {
  return {
    response: function (response) {
      // Check if backend explicitly signals session expired
      if (
        response.data &&
        response.data.status === "error" &&
        response.data.message === "Session expired"
      ) {
        var $rootScope = $injector.get("$rootScope");
        $rootScope.$broadcast("sessionExpired");
      }
      return response || $q.when(response);
    },

    responseError: function (response) {
      // Pass through other HTTP errors (500, 404, etc.)
      return $q.reject(response);
    },
  };
});

//#region applyStyles
myApp.directive("applyStyles", function ($timeout, $document) {
  return {
    restrict: "A",
    link: function (scope, element, attrs) {
      scope.$watch(
        function () {
          return element.attr("data-bs-toggle");
        },
        function (bsToggleAttr) {
          if (!bsToggleAttr) return;

          $timeout(function () {
            // Tooltip logic
            if (bsToggleAttr === "tooltip") {
              const tooltip = bootstrap.Tooltip.getOrCreateInstance(
                element[0],
                {
                  title: attrs.title,
                  placement: element.attr("data-bs-placement") || "top",
                  trigger: "hover",
                },
              );

              element.on("mouseleave", () => tooltip.hide());
              element.on("click", () => tooltip.hide());
            }

            // Popover logic
            if (bsToggleAttr === "popover") {
              let showTimeout;
              let hideTimeout;

              let isOverElement = false;
              let isOverPopover = false;

              const popover = bootstrap.Popover.getOrCreateInstance(
                element[0],
                {
                  title: attrs.title,
                  html: true,
                  content: function () {
                    return scope.$eval(attrs.popoverContent) || "";
                  },
                  placement: element.attr("data-bs-placement") || "top",
                  trigger: "manual",
                  container: element.attr("data-bs-container") || "body",
                },
              );

              function hidePopover() {
                clearTimeout(hideTimeout);

                hideTimeout = setTimeout(function () {
                  if (!isOverElement && !isOverPopover) {
                    popover.hide();
                  }
                }, 300);
              }

              // Mouse enter input
              element.on("mouseenter", function () {
                clearTimeout(hideTimeout);

                showTimeout = setTimeout(function () {
                  isOverElement = true;
                  popover.show();
                }, 150);
              });

              // Mouse leave input
              element.on("mouseleave", function () {
                clearTimeout(showTimeout);

                isOverElement = false;

                hidePopover();
              });

              // Mouse enter popover
              $document.on("mouseenter", ".popover", function () {
                clearTimeout(hideTimeout);

                isOverPopover = true;
              });

              // Mouse leave popover
              $document.on("mouseleave", ".popover", function () {
                isOverPopover = false;

                hidePopover();
              });
            }
          }, 0);
        },
      );
    },
  };
});

//#region counterValue
myApp.directive("counter", function ($timeout, $filter) {
  return {
    restrict: "A",
    scope: {
      counterValue: "=counter",
      counterPrefix: "@?", // ✅ Optional prefix (e.g., ₱)
    },
    link: function (scope, element) {
      scope.$watch("counterValue", function (newValue) {
        if (
          newValue !== null &&
          newValue !== undefined &&
          newValue !== scope.previousValue
        ) {
          scope.previousValue = newValue;
          animateNumberSmooth(element, newValue);
        }
      });

      function animateNumberSmooth(element, targetValue) {
        let startValue = 0;
        let duration = 1000; // 1 second fixed duration
        let startTime = null;

        function animationStep(timestamp) {
          if (!startTime) startTime = timestamp;

          let progress = timestamp - startTime;
          let percentage = Math.min(progress / duration, 1);

          // Easing function for smoother animation
          let easedPercentage = easeOutQuad(percentage);

          let currentValue =
            startValue + (targetValue - startValue) * easedPercentage;

          // ✅ Apply the prefix if it exists, otherwise just the number
          const prefix = scope.counterPrefix ? scope.counterPrefix + " " : "";
          const formattedValue = $filter("formatNumber")(
            Math.round(currentValue),
          );
          element.text(prefix + formattedValue);

          if (progress < duration) {
            requestAnimationFrame(animationStep);
          } else {
            // Ensure final value is exact and formatted
            const finalFormattedValue = $filter("formatNumber")(targetValue);
            element.text(prefix + finalFormattedValue);
          }
        }

        requestAnimationFrame(animationStep);
      }

      // Easing function for smooth animation
      function easeOutQuad(t) {
        return t * (2 - t);
      }
    },
  };
});
//#endregion

//#region formatNumber
myApp.filter("formatNumber", function () {
  return function (input) {
    if (isNaN(input) || input === null || input === undefined) return input;

    const num = parseFloat(input);
    const formatted = num.toLocaleString("en-US", {
      minimumFractionDigits: 0,
      maximumFractionDigits: 2,
    });

    // Remove .00 if whole number, or trailing 0 if .10
    return formatted.replace(/\.00$|(\.\d)0$/, "$1");
  };
});

//#region capitalize
myApp.filter("capitalize", function () {
  return function (input) {
    if (!input) return "";
    // Capitalize first letter of each word
    return input
      .split(" ")
      .map(function (word) {
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
      })
      .join(" ");
  };
});

//#region uppercase
myApp.directive("uppercase", function () {
  return {
    restrict: "A",
    require: "ngModel",
    link: function (scope, element, attrs, ngModel) {
      // Convert to uppercase on input
      element.on("input", function () {
        var value = element.val();
        if (value) {
          var upperValue = value.toUpperCase();
          if (value !== upperValue) {
            element.val(upperValue);
            ngModel.$setViewValue(upperValue);
            ngModel.$render();
          }
        }
      });

      // Convert to uppercase on paste
      element.on("paste", function () {
        // Use setTimeout to let the paste complete first
        setTimeout(function () {
          var value = element.val();
          if (value) {
            var upperValue = value.toUpperCase();
            if (value !== upperValue) {
              element.val(upperValue);
              ngModel.$setViewValue(upperValue);
              ngModel.$render();
            }
          }
        }, 0);
      });

      // Initial conversion if model has value
      scope.$watch(
        function () {
          return ngModel.$modelValue;
        },
        function (value) {
          if (value && typeof value === "string") {
            var upperValue = value.toUpperCase();
            if (value !== upperValue) {
              ngModel.$setViewValue(upperValue);
              ngModel.$render();
            }
          }
        },
      );

      // Convert on blur
      element.on("blur", function () {
        var value = element.val();
        if (value) {
          var upperValue = value.toUpperCase();
          if (value !== upperValue) {
            element.val(upperValue);
            ngModel.$setViewValue(upperValue);
            ngModel.$render();
          }
        }
      });
    },
  };
});

//#region mask
myApp.filter("mask", function () {
  return function (input, options) {
    // Handle null/undefined inputs
    if (input === null || input === undefined || input === "") {
      return "";
    }

    // Convert input to string
    var str = input.toString();
    var masked = "";

    // Default options
    var defaults = {
      showFirst: 0, // Number of characters to show at the beginning
      showLast: 0, // Number of characters to show at the end
      maskChar: "*", // Character to use for masking
      maskAll: false, // If true, mask everything regardless of showFirst/showLast
    };

    // Merge provided options with defaults
    options = angular.extend({}, defaults, options);

    if (options.maskAll) {
      // Mask everything
      for (var i = 0; i < str.length; i++) {
        masked += options.maskChar;
      }
      return masked;
    }

    // Show first characters if specified
    for (var i = 0; i < options.showFirst && i < str.length; i++) {
      masked += str.charAt(i);
    }

    // Mask middle characters
    var maskLength = str.length - options.showFirst - options.showLast;
    for (var i = 0; i < maskLength && options.showFirst + i < str.length; i++) {
      masked += options.maskChar;
    }

    // Show last characters if specified
    for (
      var i = Math.max(options.showFirst, str.length - options.showLast);
      i < str.length;
      i++
    ) {
      masked += str.charAt(i);
    }

    return masked;
  };
});

//#region numberFormatCustom
myApp.filter("numberFormatCustom", function () {
  return function (value) {
    if (value === null || value === undefined || value === "") {
      return "0.00";
    }

    // Format to 5 decimals first
    let numAmt = parseFloat(value).toFixed(5);

    // Remove trailing zeros and decimal point
    numAmt = numAmt.replace(/\.?0+$/, "");

    // Ensure at least 2 decimal places
    if (numAmt.indexOf(".") !== -1) {
      let decimals = numAmt.split(".")[1].length;

      if (decimals < 2) {
        numAmt += "0".repeat(2 - decimals);
      }
    } else {
      numAmt += ".00";
    }

    // Add thousand separators
    let parts = numAmt.split(".");
    parts[0] = Number(parts[0]).toLocaleString("en-US");

    return parts.join(".");
  };
});

//#region myApp.run
myApp.run([
  "$rootScope",
  "$http",
  "$route",
  "$location",
  "$timeout",
  "$window",
  function ($rootScope, $http, $route, $location, $timeout, $window) {
    //====================================================================================
    //$region closeModal
    $rootScope.closeModal = function (modalId) {
      var modal = angular.element(document.querySelector("#" + modalId));
      modal.modal("hide");
    };

    //==============================================

    //$region hardReload
    $rootScope.hardReload = function () {
      window.location.reload(); // Standard way to reload the page
    };

    //==============================================
    //#region openNextModal
    $rootScope.openNextModal = function (modalId) {
      let modalElement = document.getElementById(modalId);
      if (modalElement) {
        let myModal = new bootstrap.Modal(modalElement, {});
        myModal.show();
      }
    };

    //==============================================
    //#region $routeChangeStart
    // Optimized route handler
    $rootScope.$on("$routeChangeStart", function (event, next) {
      Pace.restart();
    });

    // Update page title and active class on route change
    $rootScope.$on("$routeChangeSuccess", function (event, current) {
      document.title = $route.current.title; // Update page title on route change
      Pace.stop();

      //JQUERY
      $timeout(function () {
        //======================================================================
      }, 0);
    });
  },
]);
