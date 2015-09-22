/*! sprintf-js | Alexandru Marasteanu <hello@alexei.ro> (http://alexei.ro/) | BSD-3-Clause */

!function (a) {
    function b() {
        var a = arguments[0], c = b.cache;
        return c[a] && c.hasOwnProperty(a) || (c[a] = b.parse(a)), b.format.call(null, c[a], arguments)
    }

    function c(a) {
        return Object.prototype.toString.call(a).slice(8, -1).toLowerCase()
    }

    function d(a, b) {
        return Array(b + 1).join(a)
    }

    var e = {
        not_string: /[^s]/,
        number: /[diefg]/,
        json: /[j]/,
        not_json: /[^j]/,
        text: /^[^\x25]+/,
        modulo: /^\x25{2}/,
        placeholder: /^\x25(?:([1-9]\d*)\$|\(([^\)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-gijosuxX])/,
        key: /^([a-z_][a-z_\d]*)/i,
        key_access: /^\.([a-z_][a-z_\d]*)/i,
        index_access: /^\[(\d+)\]/,
        sign: /^[\+\-]/
    };
    b.format = function (a, f) {
        var g, h, i, j, k, l, m, n = 1, o = a.length, p = "", q = [], r = !0, s = "";
        for (h = 0; o > h; h++)if (p = c(a[h]), "string" === p)q[q.length] = a[h]; else if ("array" === p) {
            if (j = a[h], j[2])for (g = f[n], i = 0; i < j[2].length; i++) {
                if (!g.hasOwnProperty(j[2][i]))throw new Error(b("[sprintf] property '%s' does not exist", j[2][i]));
                g = g[j[2][i]]
            } else g = j[1] ? f[j[1]] : f[n++];
            if ("function" == c(g) && (g = g()), e.not_string.test(j[8]) && e.not_json.test(j[8]) && "number" != c(g) && isNaN(g))throw new TypeError(b("[sprintf] expecting number but found %s", c(g)));
            switch (e.number.test(j[8]) && (r = g >= 0), j[8]) {
                case"b":
                    g = g.toString(2);
                    break;
                case"c":
                    g = String.fromCharCode(g);
                    break;
                case"d":
                case"i":
                    g = parseInt(g, 10);
                    break;
                case"j":
                    g = JSON.stringify(g, null, j[6] ? parseInt(j[6]) : 0);
                    break;
                case"e":
                    g = j[7] ? g.toExponential(j[7]) : g.toExponential();
                    break;
                case"f":
                    g = j[7] ? parseFloat(g).toFixed(j[7]) : parseFloat(g);
                    break;
                case"g":
                    g = j[7] ? parseFloat(g).toPrecision(j[7]) : parseFloat(g);
                    break;
                case"o":
                    g = g.toString(8);
                    break;
                case"s":
                    g = (g = String(g)) && j[7] ? g.substring(0, j[7]) : g;
                    break;
                case"u":
                    g >>>= 0;
                    break;
                case"x":
                    g = g.toString(16);
                    break;
                case"X":
                    g = g.toString(16).toUpperCase()
            }
            e.json.test(j[8]) ? q[q.length] = g : (!e.number.test(j[8]) || r && !j[3] ? s = "" : (s = r ? "+" : "-", g = g.toString().replace(e.sign, "")), l = j[4] ? "0" === j[4] ? "0" : j[4].charAt(1) : " ", m = j[6] - (s + g).length, k = j[6] && m > 0 ? d(l, m) : "", q[q.length] = j[5] ? s + g + k : "0" === l ? s + k + g : k + s + g)
        }
        return q.join("")
    }, b.cache = {}, b.parse = function (a) {
        for (var b = a, c = [], d = [], f = 0; b;) {
            if (null !== (c = e.text.exec(b)))d[d.length] = c[0]; else if (null !== (c = e.modulo.exec(b)))d[d.length] = "%"; else {
                if (null === (c = e.placeholder.exec(b)))throw new SyntaxError("[sprintf] unexpected placeholder");
                if (c[2]) {
                    f |= 1;
                    var g = [], h = c[2], i = [];
                    if (null === (i = e.key.exec(h)))throw new SyntaxError("[sprintf] failed to parse named argument key");
                    for (g[g.length] = i[1]; "" !== (h = h.substring(i[0].length));)if (null !== (i = e.key_access.exec(h)))g[g.length] = i[1]; else {
                        if (null === (i = e.index_access.exec(h)))throw new SyntaxError("[sprintf] failed to parse named argument key");
                        g[g.length] = i[1]
                    }
                    c[2] = g
                } else f |= 2;
                if (3 === f)throw new Error("[sprintf] mixing positional and named placeholders is not (yet) supported");
                d[d.length] = c
            }
            b = b.substring(c[0].length)
        }
        return d
    };
    var f = function (a, c, d) {
        return d = (c || []).slice(0), d.splice(0, 0, a), b.apply(null, d)
    };
    "undefined" != typeof exports ? (exports.sprintf = b, exports.vsprintf = f) : (a.sprintf = b, a.vsprintf = f, "function" == typeof define && define.amd && define(function () {
        return {sprintf: b, vsprintf: f}
    }))
}("undefined" == typeof window ? this : window);
//# sourceMappingURL=sprintf.min.map
jQuery(document).ready(function ($) {
    phone = $('input[name="Phone"]');
    email = $('input[name="Username"]');
    var $add_membership = $('#add_membership');

    //phone.parent().hide();

    email.attr('placeholder', '_@_');
    //email after timezone
    var $email = $('input[name="user_email"]').parent('div.pure-control-group');
    //var $time_zone = $(':input[name="Time zone"]').parent('div.pure-control-group');
    //$time_zone.after($email);

    //brian3t paypal extend life
    //redirect here
    /*
     @var int user_id
     */
    $add_membership.click(function (event) {
        if (typeof $user_id == "undefined") {
            console.log("no user id");
            return;
        }
        var agreement_xhr = $.get('/pp/billing/CreateBillingAgreementWithPayPal.php', {user_id: $user_id});
        agreement_xhr.done(function ($agreement) {
            console.log($agreement);
            if ($agreement.plan.state == "ACTIVE") {
                location.href = $agreement.links[0].href;
            }
        })

    });
    var repeated_bf = $(':input[name="Breakfast"]');
    var repeated_lunch = $(':input[name="Lunch"]');
    var repeated_dinner = $(':input[name="Dinner"]');
    var repeated_tz = $(':input[name="Time zone"]');
    $(repeated_bf[0]).change(function (event) {
        $(repeated_bf[1]).val((event.currentTarget.value));
    });
    $(repeated_bf[1]).change(function (event) {
        $(repeated_bf[0]).val((event.currentTarget.value));
    });
    $(repeated_lunch[0]).change(function (event) {
        $(repeated_lunch[1]).val((event.currentTarget.value));
    });
    $(repeated_lunch[1]).change(function (event) {
        $(repeated_lunch[0]).val((event.currentTarget.value));
    });
    $(repeated_dinner[0]).change(function (event) {
        $(repeated_dinner[1]).val((event.currentTarget.value));
    });
    $(repeated_dinner[1]).change(function (event) {
        $(repeated_dinner[0]).val((event.currentTarget.value));
    });
    $(repeated_tz[0]).change(function (event) {
        $(repeated_tz[1]).val((event.currentTarget.value));
    });
    $(repeated_tz[1]).change(function (event) {
        $(repeated_tz[0]).val((event.currentTarget.value));
    });
});