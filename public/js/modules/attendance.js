/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/modules/attendance.js":
/*!********************************************!*\
  !*** ./resources/js/modules/attendance.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
/**
 * Attendance Module
 * Handles QR scanning, geolocation, check-in, beacons, and keyword challenges
 */

var AttendanceModule = function () {
  var beaconInterval = null;
  var currentSessionId = null;
  var scanner = null;

  /**
   * Initialize the attendance module
   */
  function init() {
    setupEventListeners();
  }

  /**
   * Setup event listeners for check-in buttons
   */
  function setupEventListeners() {
    var scanQRBtn = document.getElementById('scanQR');
    var joinRemoteBtn = document.getElementById('joinRemote');
    var stopScannerBtn = document.getElementById('stopScanner');
    var submitKeywordBtn = document.getElementById('submitKeyword');
    if (scanQRBtn) {
      scanQRBtn.addEventListener('click', handleScanQR);
    }
    if (joinRemoteBtn) {
      joinRemoteBtn.addEventListener('click', handleJoinRemote);
    }
    if (stopScannerBtn) {
      stopScannerBtn.addEventListener('click', stopScanner);
    }
    if (submitKeywordBtn) {
      submitKeywordBtn.addEventListener('click', handleSubmitKeyword);
    }
  }

  /**
   * Handle QR code scanning
   */
  function handleScanQR() {
    return _handleScanQR.apply(this, arguments);
  }
  /**
   * Handle remote join
   */
  function _handleScanQR() {
    _handleScanQR = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee() {
      var position, video, canvas, qrScannerDiv, _t;
      return _regenerator().w(function (_context) {
        while (1) switch (_context.p = _context.n) {
          case 0:
            _context.p = 0;
            _context.n = 1;
            return getCurrentPosition();
          case 1:
            position = _context.v;
            // Initialize QR scanner
            video = document.getElementById('video');
            canvas = document.getElementById('canvas');
            qrScannerDiv = document.getElementById('qrScanner');
            if (!(!video || !canvas)) {
              _context.n = 2;
              break;
            }
            showStatus('Error: Video or canvas element not found', 'danger');
            return _context.a(2);
          case 2:
            qrScannerDiv.style.display = 'block';
            video.style.display = 'block';

            // Use qr-scanner library if available, otherwise use basic approach
            if (!(typeof QrScanner !== 'undefined')) {
              _context.n = 4;
              break;
            }
            scanner = new QrScanner(video, function (result) {
              handleQRResult(result, 'onsite', position);
            });
            _context.n = 3;
            return scanner.start();
          case 3:
            _context.n = 5;
            break;
          case 4:
            // Fallback: manual QR scanning would require additional library
            showStatus('QR Scanner library not loaded. Please install qr-scanner.', 'warning');
          case 5:
            _context.n = 7;
            break;
          case 6:
            _context.p = 6;
            _t = _context.v;
            console.error('Error starting QR scanner:', _t);
            showStatus('Error accessing camera: ' + _t.message, 'danger');
          case 7:
            return _context.a(2);
        }
      }, _callee, null, [[0, 6]]);
    }));
    return _handleScanQR.apply(this, arguments);
  }
  function handleJoinRemote() {
    return _handleJoinRemote.apply(this, arguments);
  }
  /**
   * Handle QR scan result
   */
  function _handleJoinRemote() {
    _handleJoinRemote = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2() {
      var position, sessionId, _t2;
      return _regenerator().w(function (_context2) {
        while (1) switch (_context2.p = _context2.n) {
          case 0:
            _context2.p = 0;
            _context2.n = 1;
            return getCurrentPosition();
          case 1:
            position = _context2.v;
            // For remote, we need to get the session ID from URL or prompt
            sessionId = prompt('Enter session ID:');
            if (sessionId) {
              _context2.n = 2;
              break;
            }
            return _context2.a(2);
          case 2:
            // In a real implementation, you'd get the remote token from the server
            // For now, we'll simulate it
            showStatus('Remote join functionality requires session token', 'info');
            _context2.n = 4;
            break;
          case 3:
            _context2.p = 3;
            _t2 = _context2.v;
            console.error('Error joining remote:', _t2);
            showStatus('Error: ' + _t2.message, 'danger');
          case 4:
            return _context2.a(2);
        }
      }, _callee2, null, [[0, 3]]);
    }));
    return _handleJoinRemote.apply(this, arguments);
  }
  function handleQRResult(_x, _x2, _x3) {
    return _handleQRResult.apply(this, arguments);
  }
  /**
   * Get current geolocation
   */
  function _handleQRResult() {
    _handleQRResult = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3(token, mode, position) {
      var checkInData, response, _error$response, message, _t3;
      return _regenerator().w(function (_context3) {
        while (1) switch (_context3.p = _context3.n) {
          case 0:
            stopScanner();
            _context3.p = 1;
            checkInData = {
              token: token,
              mode: mode
            };
            if (position) {
              checkInData.lat = position.coords.latitude;
              checkInData.lng = position.coords.longitude;
              checkInData.geo_confidence = 0.9; // High confidence for GPS
            }
            _context3.n = 2;
            return axios.post('/attendance/check-in', checkInData);
          case 2:
            response = _context3.v;
            if (response.data.attendance) {
              currentSessionId = response.data.attendance.training_session_id;
              showStatus('Checked in successfully!', 'success');
              startBeacons(currentSessionId);
            }
            _context3.n = 4;
            break;
          case 3:
            _context3.p = 3;
            _t3 = _context3.v;
            console.error('Check-in error:', _t3);
            message = ((_error$response = _t3.response) === null || _error$response === void 0 || (_error$response = _error$response.data) === null || _error$response === void 0 ? void 0 : _error$response.error) || 'Check-in failed';
            showStatus(message, 'danger');
          case 4:
            return _context3.a(2);
        }
      }, _callee3, null, [[1, 3]]);
    }));
    return _handleQRResult.apply(this, arguments);
  }
  function getCurrentPosition() {
    return new Promise(function (resolve, reject) {
      if (!navigator.geolocation) {
        reject(new Error('Geolocation is not supported by this browser'));
        return;
      }
      navigator.geolocation.getCurrentPosition(resolve, reject, {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
      });
    });
  }

  /**
   * Start sending beacon heartbeats every 2 minutes
   */
  function startBeacons(sessionId) {
    if (beaconInterval) {
      clearInterval(beaconInterval);
    }

    // Send initial beacon
    sendBeacon(sessionId);

    // Then send every 2 minutes (120000 ms)
    beaconInterval = setInterval(function () {
      sendBeacon(sessionId);
    }, 120000);
  }

  /**
   * Send a beacon heartbeat
   */
  function sendBeacon(_x4) {
    return _sendBeacon.apply(this, arguments);
  }
  /**
   * Stop QR scanner
   */
  function _sendBeacon() {
    _sendBeacon = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee4(sessionId) {
      var beaconData, position, _t4, _t5;
      return _regenerator().w(function (_context4) {
        while (1) switch (_context4.p = _context4.n) {
          case 0:
            _context4.p = 0;
            beaconData = {}; // Try to get current position for beacon
            _context4.p = 1;
            _context4.n = 2;
            return getCurrentPosition();
          case 2:
            position = _context4.v;
            beaconData.lat = position.coords.latitude;
            beaconData.lng = position.coords.longitude;
            _context4.n = 4;
            break;
          case 3:
            _context4.p = 3;
            _t4 = _context4.v;
            // Geolocation failed, send without coordinates
            console.warn('Could not get position for beacon:', _t4);
          case 4:
            _context4.n = 5;
            return axios.post("/sessions/".concat(sessionId, "/beacon"), beaconData);
          case 5:
            console.log('Beacon sent successfully');
            _context4.n = 7;
            break;
          case 6:
            _context4.p = 6;
            _t5 = _context4.v;
            console.error('Beacon error:', _t5);
            // Don't show error to user for beacons, just log
          case 7:
            return _context4.a(2);
        }
      }, _callee4, null, [[1, 3], [0, 6]]);
    }));
    return _sendBeacon.apply(this, arguments);
  }
  function stopScanner() {
    if (scanner) {
      scanner.stop();
      scanner = null;
    }
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    var qrScannerDiv = document.getElementById('qrScanner');
    if (video) {
      video.style.display = 'none';
      var stream = video.srcObject;
      if (stream) {
        stream.getTracks().forEach(function (track) {
          return track.stop();
        });
      }
    }
    if (qrScannerDiv) {
      qrScannerDiv.style.display = 'none';
    }
  }

  /**
   * Handle keyword challenge submission
   */
  function handleSubmitKeyword() {
    return _handleSubmitKeyword.apply(this, arguments);
  }
  /**
   * Get session ID from URL or context
   */
  function _handleSubmitKeyword() {
    _handleSubmitKeyword = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee5() {
      var keywordInput, keyword, sessionId, response, _error$response2, message, _t6;
      return _regenerator().w(function (_context5) {
        while (1) switch (_context5.p = _context5.n) {
          case 0:
            keywordInput = document.getElementById('keywordInput');
            keyword = keywordInput.value.trim().toUpperCase();
            if (!(keyword.length !== 6)) {
              _context5.n = 1;
              break;
            }
            showStatus('Keyword must be 6 characters', 'danger');
            return _context5.a(2);
          case 1:
            // Get session ID from URL or context
            sessionId = getSessionIdFromContext();
            if (sessionId) {
              _context5.n = 2;
              break;
            }
            showStatus('Session ID not found', 'danger');
            return _context5.a(2);
          case 2:
            _context5.p = 2;
            _context5.n = 3;
            return axios.post("/sessions/".concat(sessionId, "/challenge"), {
              keyword: keyword
            });
          case 3:
            response = _context5.v;
            showStatus('Challenge passed successfully!', 'success');
            keywordInput.value = '';
            _context5.n = 5;
            break;
          case 4:
            _context5.p = 4;
            _t6 = _context5.v;
            console.error('Challenge error:', _t6);
            message = ((_error$response2 = _t6.response) === null || _error$response2 === void 0 || (_error$response2 = _error$response2.data) === null || _error$response2 === void 0 ? void 0 : _error$response2.error) || 'Invalid keyword';
            showStatus(message, 'danger');
          case 5:
            return _context5.a(2);
        }
      }, _callee5, null, [[2, 4]]);
    }));
    return _handleSubmitKeyword.apply(this, arguments);
  }
  function getSessionIdFromContext() {
    // Try to get from URL parameter
    var urlParams = new URLSearchParams(window.location.search);
    var sessionId = urlParams.get('session_id');
    if (sessionId) {
      return sessionId;
    }

    // Try to get from current session ID if set
    return currentSessionId;
  }

  /**
   * Show status message
   */
  function showStatus(message, type) {
    var statusDiv = document.getElementById('statusMessage');
    if (!statusDiv) {
      return;
    }
    var alertClass = "alert alert-".concat(type);
    statusDiv.innerHTML = "<div class=\"".concat(alertClass, "\">").concat(message, "</div>");

    // Auto-hide after 5 seconds
    setTimeout(function () {
      statusDiv.innerHTML = '';
    }, 5000);
  }

  /**
   * Show keyword input section
   */
  function showKeywordSection() {
    var keywordSection = document.getElementById('keywordSection');
    if (keywordSection) {
      keywordSection.style.display = 'block';
    }
  }

  // Public API
  return {
    init: init,
    showKeywordSection: showKeywordSection
  };
}();

// Auto-initialize if DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', AttendanceModule.init);
} else {
  AttendanceModule.init();
}

// Export for use in other scripts
if ( true && module.exports) {
  module.exports = AttendanceModule;
}

/***/ }),

/***/ 1:
/*!**************************************************!*\
  !*** multi ./resources/js/modules/attendance.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\rawan.abuseini\Desktop\attendance\resources\js\modules\attendance.js */"./resources/js/modules/attendance.js");


/***/ })

/******/ });