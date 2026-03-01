# SimpleSMTP

The minimal SMTP plugin ever. Connect your blog with an SMTP service so every email
is sent via that service.

## Install

The simple installation instruction can be found in the [official page](https://www.satollo.net/plugins/simplesmtp).

## Notes

The plugin, once uninstalled, does not leave traces on your site.

No changes are made to emails content/subject/headers, it just redirects the email
to the SMTP service. Anyway other plugins can modify the emails content.

Connection errors (like "unable to connect" or "timeout") are related your SMTP
paramaters (check them or use a different set of ports/protocols) or a firewall block
by your hosting provider, contact them!

