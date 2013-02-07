<?php
namespace Yamw\Lib\Mongo;

final class MapReduce
{
    public static function getUserAgents()
    {
        return AdvMongo::group(
            'yamw_stats',
            array('global_server.HTTP_USER_AGENT' => true),
            array('count' => 0),
            "function(obj, prev) {prev.count++;}"
        );
    }

    public static function getReferers() {
        return AdvMongo::group(
            'yamw_stats',
            array('global_server.HTTP_REFERER' => true),
            array('count' => 0),
            "function(obj, prev) {prev.count++;}"
        );
    }

    public static function getAvgPerPage($group = 'main')
    {
        return AdvMongo::mapReduce(
            array(
                'mapreduce' => 'yamw_stats',

                'map' => "function () {
                    emit(this.page, {count: 1, max_mem: this.max_memory,
                    pagetime: this.pagetime, queries: this.numqueries,
                    template: this.uses_template});
                }",

                'reduce' => 'function (key, values) {
                    var count = 0;
                    var max_mem = 0;
                    var pagetime = 0;
                    var queries = 0;
                    var template = false;

                    values.forEach(function (doc) {
                        count += doc.count;
                        max_mem += doc.max_mem;
                        pagetime += doc.pagetime;
                        queries += doc.queries;
                        template = doc.template;
                    });

                    return {count: count, max_mem: max_mem, pagetime: pagetime,
                    queries: queries, template: template};
                }',

                'finalize' => 'function (t, doc) {
                    doc.avg_mem = doc.max_mem / doc.count;
                    doc.avg_time = doc.pagetime / doc.count;
                    doc.avg_queries = doc.queries / doc.count;

                    return doc;
                }',

                'query' => array('statgroup' => new \MongoRegex("/^$group$/")),

                'jsMode' => true,

                'out' => 'yamw_stats_proc'
            )
        );
    }

    public static function getWebLink($group = 'main')
    {
        return AdvMongo::mapReduce(
            array(
                'mapreduce' => 'yamw_stats',

                'map' => "function () {
                    emit(this.page, {referer:
                        {page: this.global_server.HTTP_REFERER, count: 1}, count: 1});
                }",

                'reduce' => 'function (key, values) {
                    var count = 0;
                    var referers = [];
                    var added = false;

                    values.forEach(function (doc) {
                        count += doc.count;
                        added = false;
                        referers.forEach(function (d) {
                            if(d.page == doc.referer.page) {
                                d.count += doc.referer.count;
                                added = true;
                            }
                        });
                        if(!added) referers.push({page: doc.referer.page,
                        count: doc.referer.count});
                    });

                    return {count: count, referer: referers};
                }',

                'query' => array('statgroup' => new \MongoRegex("/^$group$/")),

                'jsMode' => true,

                'out' => 'yamw_stats_web_link'
            )
        );
    }

    public static function getRevWebLink($group = 'main')
    {
        return AdvMongo::mapReduce(
            array(
                'mapreduce' => 'yamw_stats',

                'map' => "function () {
                    emit(this.global_server.HTTP_REFERER, {goto: {page: this.page, count: 1}, count: 1});
                }",

                'reduce' => 'function (key, values) {
                    var count = 0;
                    var referers = [];
                    var added = false;

                    values.forEach(function (doc) {
                        count += doc.count;
                        added = false;
                        referers.forEach(function (d) {
                            if(d.page == doc.goto.page) {
                                d.count += doc.goto.count;
                                added = true;
                            }
                        });
                        if(!added) referers.push({page: doc.goto.page, count: doc.goto.count});
                    });

                    return {count: count, goto: referers};
                }',

                'query' => array('statgroup' => new \MongoRegex("/^$group$/")),

                'jsMode' => true,

                'out' => 'yamw_stats_web_link_rev'
            )
        );
    }

    public static function getWebLinkTrace($group = 'main')
    {
        return AdvMongo::mapReduce(
            array(
                'mapreduce' => 'yamw_stats',

                'map' => "function () {
                    emit(this.global_server.REMOTE_ADDR + '--' + this.global_server.HTTP_USER_AGENT,
                [{useragent: this.global_server.HTTP_USER_AGENT, page: this.page,
                referer: this.global_server.HTTP_REFERER, time: this.time}]);
                }",

                'reduce' => 'function (key, values) {
                    var data = [];

                    values.forEach(function (doc) {
                        data.push(doc);
                    });

                    return data;
                }',

                'query' => array('statgroup' => new \MongoRegex("/^$group$/")),

                'jsMode' => true,

                'out' => 'yamw_stats_web_link_trace'
            )
        );
    }
}
