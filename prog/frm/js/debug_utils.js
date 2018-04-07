//***********************************************************************************************************//

if(!("console" in window) || !("firebug" in console)) {
    var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml", "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"];

    window.console = {};

    for(var i = 0; i < names.length; ++i) window.console[names[i]] = function() {};
}

function trace(msg) {
        if(console)
		console.log(msg);
}

function inspect(obj) {
	if(console)
		console.dir(obj);
}

//***********************************************************************************************************//
