---
layout: page
title: Logger stuff
---

Make sure you log your thing and only your thing.

Don't sound like a robot. Humans read the activity feed, so write for them.

Store context. Don't store too much if log entry is common, since it takes up space in the database. Store just enough amount of data to make context useful for debugging purposes.
Store non-translated strings and data, and translate them when viewing the log.
Store message with enough info to make it clear what the message is about, but don't.

Always make sure variables and keys in arrays and objects exists by using`isset()`. Also make sure to develop with `WP_DEBUG` set to true. We don't want any users to get PHP warnings or notices.

Remember that plain text can be used for CSV-export and similar, so text must work without links too. Links are enhancement.
