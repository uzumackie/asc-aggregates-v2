(function ($) {
  "use strict";

  // ========= DATA =========
  const segments = [
    {
      key: "Social Media",
      title: "Social Media",
      pct: 45,
      color: "#FF7433",
      valueText: "3,432",
      deltaText: "5.6%",
    },
    {
      key: "Website",
      title: "Website",
      pct: 20,
      color: "#2377FC",
      valueText: "1,016",
      deltaText: "2.1%",
    },
    {
      key: "Store",
      title: "Store",
      pct: 35,
      color: "#8F77F3",
      valueText: "172",
      deltaText: "0.8%",
    },
  ];

  // Default center content
  const base = { ...segments[0] };

  // ========= VARIABLES =========
  let donut = null;
  let resizeTimer = null;
  let observer = null;
  let currentContainer = null;

  // ========= CENTER CONTENT =========
  function setCenter(item) {
    $("#centerBadge").css("border-color", item.color);
    $("#centerTitle").text(item.title);
    $("#centerValue").text(item.valueText);
    $("#centerDelta").text(item.deltaText);
    $("#centerDeltaWrap").css("color", item.color);
  }

  // ========= HOVER EVENTS =========
  function bindHover() {
    const $svg = $("#morris-donut-1 svg");

    if (!$svg.length) {
      return;
    }

    let $paths = $svg.find("path.morris-donut-segment");

    // Fallback for Morris.js versions with different SVG classes.
    if (!$paths.length) {
      $paths = $svg.find("path").filter(function () {
        const fill = $(this).attr("fill");
        return fill && fill !== "none";
      });
    }

    $paths.each(function (index) {
      $(this).attr("data-idx", index).css("cursor", "pointer");
    });

    // Remove old handlers to prevent duplicate events.
    $svg.off(".donutHover");

    $svg.on("mouseenter.donutHover", "path[data-idx]", function () {
      const index = Number($(this).attr("data-idx"));
      const item = segments[index];

      if (item) {
        setCenter(item);
      }
    });
  }

  // ========= RENDER DONUT =========
  function renderDonut() {
    const chartElement = document.getElementById("morris-donut-1");

    // The AngularJS view may not exist yet.
    if (!chartElement) {
      return;
    }

    // Avoid reinitializing the chart in the same container.
    if (donut && currentContainer === chartElement) {
      return;
    }

    // Clean up the previous chart instance.
    if (donut) {
      donut = null;
    }

    $(chartElement).empty();

    // Make sure Morris.js is loaded.
    if (typeof Morris === "undefined" || !Morris.Donut) {
      console.error("Morris.js is not loaded.");
      return;
    }

    try {
      donut = new Morris.Donut({
        element: chartElement,
        data: segments.map(function (segment) {
          return {
            label: segment.key,
            value: segment.pct,
          };
        }),
        colors: segments.map(function (segment) {
          return segment.color;
        }),
        formatter: function (value) {
          return value + "%";
        },
        resize: true,
      });

      currentContainer = chartElement;

      setCenter(base);

      // Bind hover after Morris.js creates the SVG.
      setTimeout(bindHover, 0);
    } catch (error) {
      console.error("Unable to render the donut chart:", error);
    }
  }

  // ========= INITIALIZE WHEN CONTAINER EXISTS =========
  function initDonut() {
    const chartElement = document.getElementById("morris-donut-1");

    if (!chartElement) {
      return;
    }

    // Observe changes to the chart container's parent so the
    // chart can be initialized when AngularJS inserts the view.
    if (observer) {
      observer.disconnect();
      observer = null;
    }

    renderDonut();
  }

  // ========= WATCH FOR ANGULARJS VIEW CHANGES =========
  function watchForDashboard() {
    // Try immediately in case the dashboard is already present.
    initDonut();

    // Observe DOM changes because ng-view inserts dashboard HTML
    // after the initial JavaScript file may have executed.
    if (!observer && document.body) {
      observer = new MutationObserver(function () {
        const chartElement = document.getElementById("morris-donut-1");

        if (chartElement && chartElement !== currentContainer) {
          renderDonut();
        }
      });

      observer.observe(document.body, {
        childList: true,
        subtree: true,
      });
    }
  }

  // ========= WINDOW RESIZE =========
  $(window).off("resize.donutChart");

  $(window).on("resize.donutChart", function () {
    clearTimeout(resizeTimer);

    resizeTimer = setTimeout(function () {
      const chartElement = document.getElementById("morris-donut-1");

      if (!chartElement) {
        return;
      }

      if (donut && currentContainer === chartElement) {
        // Morris handles resizing because resize: true is enabled.
        setTimeout(bindHover, 0);
      } else {
        renderDonut();
      }
    }, 200);
  });

  // ========= START =========
  $(function () {
    watchForDashboard();
  });
})(jQuery);
