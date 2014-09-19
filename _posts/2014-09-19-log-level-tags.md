---
layout: post
title: Colourful Log Level Tags implemented
---

Since Simple History uses the log levels from [The Syslog Protocol](http://tools.ietf.org/html/rfc5424#page-11), each log entry can be one of 8 severitys. 

For example a failed login attempt may be of severity `warning`, but a successful login may only be of severity `notice`.

In the output we need a way to show the log level of each entry. In Simple History I've decided to show the log levels as an inline "tag", the same way as GitHub does it. I played around for a long time, trying out some other ways of displaying it, but I finally settled with this. 

It's simple, it's easy, it's colorful. And it's useful.

![Screenshot of Simple History and it's colorful tags](/assets/29-sep-2014-log-level-tags.png)


