var JSON = JSON || {};
// implement JSON.stringify serialization
JSON.stringify = JSON.stringify || function(obj) {
    var t = typeof (obj);
    if (t != "object" || obj === null) {
        // simple data type
        if (t == "string")
            obj = '"' + obj + '"';
        return String(obj);
    } else {
        // recurse array or object
        var n, v, json = [], arr = (obj && obj.constructor == Array);
        for (n in obj) {
            v = obj[n];
            t = typeof (v);
            if (t == "string")
                v = '"' + v + '"';
            else if (t == "object" && v !== null)
                v = JSON.stringify(v);
            json.push((arr ? "" : '"' + n + '":') + String(v));
        }
        return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
    }
};
// implement JSON.parse de-serialization
JSON.parse = JSON.parse || function() {
    var r = "(?:-?\\b(?:0|[1-9][0-9]*)(?:\\.[0-9]+)?(?:[eE][+-]?[0-9]+)?\\b)", k = '(?:[^\\0-\\x08\\x0a-\\x1f"\\\\]|\\\\(?:["/\\\\bfnrt]|u[0-9A-Fa-f]{4}))';
    k = '(?:"' + k + '*")';
    var s = new RegExp(
            "(?:false|true|null|[\\{\\}\\[\\]]|" + r + "|" + k + ")", "g"), t = new RegExp(
            "\\\\(?:([^u])|u(.{4}))", "g"), u = {
        '"' : '"',
        "/" : "/",
        "\\" : "\\",
        b : "\u0008",
        f : "\u000c",
        n : "\n",
        r : "\r",
        t : "\t"
    };
    function v(h, j, e) {
        return j ? u[j] : String.fromCharCode(parseInt(e, 16));
    }
    var w = new String(""), x = Object.hasOwnProperty;
    return function(h, j) {
        h = h.match(s);
        var e, c = h[0], l = false;
        if ("{" === c)
            e = {};
        else if ("[" === c)
            e = [];
        else {
            e = [];
            l = true;
        }
        for ( var b, d = [ e ], m = 1 - l, y = h.length; m = 0;)
                            delete f[i[g]];
                }
                return j.call(n, o, f);
            };
            e = p({
                "" : e
            }, "");
        }
        return e;
    };
}();