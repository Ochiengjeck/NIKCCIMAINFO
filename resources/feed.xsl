<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:atom="http://www.w3.org/2005/Atom">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"
        doctype-system="about:legacy-compat"/>

    <xsl:template match="/rss/channel">
        <html lang="en">
        <head>
            <meta charset="UTF-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <title><xsl:value-of select="title"/> — RSS Feed</title>
            <link rel="preconnect" href="https://fonts.bunny.net"/>
            <link href="https://fonts.bunny.net/css?family=playfair-display:600,700,800|instrument-sans:400,500,600" rel="stylesheet"/>
            <style>
                :root {
                    --crimson: #9f1239;
                    --crimson-dark: #6f0f2e;
                    --ink: #18181b;
                    --muted: #52525b;
                    --faint: #a1a1aa;
                    --line: #e4e4e7;
                    --bg: #f4f4f5;
                }
                * { box-sizing: border-box; }
                html { -webkit-text-size-adjust: 100%; }
                body {
                    margin: 0;
                    background: var(--bg);
                    color: var(--ink);
                    font-family: 'Instrument Sans', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
                    line-height: 1.6;
                }
                a { color: inherit; text-decoration: none; }
                .serif { font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; }

                /* Hero */
                .hero {
                    position: relative;
                    overflow: hidden;
                    color: #fff;
                    background: linear-gradient(135deg, #18181b 0%, #27272a 45%, var(--crimson-dark) 100%);
                    padding: 64px 24px 56px;
                }
                .hero::after {
                    content: "";
                    position: absolute; inset: 0;
                    background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.07) 1px, transparent 0);
                    background-size: 22px 22px;
                    pointer-events: none;
                }
                .hero-inner { position: relative; max-width: 860px; margin: 0 auto; }
                .badge {
                    display: inline-flex; align-items: center; gap: 8px;
                    background: rgba(255,255,255,.12);
                    border: 1px solid rgba(255,255,255,.18);
                    color: #fff;
                    font-size: 12px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase;
                    padding: 6px 12px; border-radius: 999px; margin-bottom: 20px;
                }
                .badge svg { width: 14px; height: 14px; }
                .hero h1 { font-size: clamp(30px, 5vw, 46px); font-weight: 800; margin: 0 0 12px; line-height: 1.1; }
                .hero p { margin: 0; color: #d4d4d8; font-size: 17px; max-width: 620px; }

                /* Subscribe bar */
                .subscribe {
                    max-width: 860px; margin: -28px auto 0; position: relative; z-index: 2;
                    background: #fff; border: 1px solid var(--line);
                    border-radius: 16px; padding: 18px 22px;
                    box-shadow: 0 12px 30px -12px rgba(0,0,0,.18);
                    display: flex; gap: 16px; align-items: flex-start; flex-wrap: wrap;
                }
                .subscribe .ic {
                    flex: none; width: 40px; height: 40px; border-radius: 12px;
                    display: flex; align-items: center; justify-content: center;
                    background: #fdeef2; color: var(--crimson);
                }
                .subscribe .ic svg { width: 20px; height: 20px; }
                .subscribe .txt { flex: 1 1 240px; min-width: 0; }
                .subscribe .txt strong { display: block; font-size: 14px; }
                .subscribe .txt span { font-size: 13px; color: var(--muted); }
                .subscribe code {
                    display: inline-block; margin-top: 8px; max-width: 100%;
                    overflow-x: auto; white-space: nowrap;
                    font-size: 12px; color: var(--muted);
                    background: var(--bg); border: 1px solid var(--line);
                    border-radius: 8px; padding: 6px 10px;
                    font-family: ui-monospace, 'SFMono-Regular', Menlo, Consolas, monospace;
                }

                /* List */
                .wrap { max-width: 860px; margin: 0 auto; padding: 40px 24px 72px; }
                .count { font-size: 12px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: var(--crimson); margin: 0 0 18px; }
                .item {
                    background: #fff; border: 1px solid var(--line); border-radius: 16px;
                    padding: 22px 24px; margin-bottom: 16px;
                    transition: box-shadow .2s ease, transform .2s ease, border-color .2s ease;
                }
                .item:hover { box-shadow: 0 14px 30px -16px rgba(0,0,0,.22); transform: translateY(-2px); border-color: #d4d4d8; }
                .meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 10px; }
                .cat {
                    font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
                    color: var(--crimson); background: #fdeef2; padding: 3px 9px; border-radius: 999px;
                }
                .date { font-size: 13px; color: var(--faint); }
                .item h2 { margin: 0 0 8px; font-size: 21px; font-weight: 700; line-height: 1.3; }
                .item h2 a:hover { color: var(--crimson); }
                .item p { margin: 0 0 14px; color: var(--muted); font-size: 15px; }
                .more { display: inline-flex; align-items: center; gap: 6px; font-size: 14px; font-weight: 600; color: var(--crimson); }
                .more svg { width: 15px; height: 15px; transition: transform .15s ease; }
                .item:hover .more svg { transform: translateX(3px); }

                .empty {
                    background: #fff; border: 1px dashed var(--line); border-radius: 16px;
                    padding: 48px 24px; text-align: center; color: var(--muted);
                }

                footer { text-align: center; padding: 0 24px 56px; }
                footer a { display: inline-flex; align-items: center; gap: 6px; color: var(--crimson); font-weight: 600; font-size: 14px; }
                footer svg { width: 15px; height: 15px; }
            </style>
        </head>
        <body>
            <div class="hero">
                <div class="hero-inner">
                    <span class="badge">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M4 11a9 9 0 019 9h-2.5A6.5 6.5 0 004 13.5V11zm0-5a14 14 0 0114 14h-2.5A11.5 11.5 0 004 8.5V6zm1.5 9a2 2 0 110 4 2 2 0 010-4z"/></svg>
                        RSS Feed
                    </span>
                    <h1 class="serif"><xsl:value-of select="title"/></h1>
                    <p><xsl:value-of select="description"/></p>
                </div>
            </div>

            <div class="subscribe">
                <span class="ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </span>
                <div class="txt">
                    <strong>This is a web feed, also known as an RSS feed.</strong>
                    <span>Subscribe by copying the URL below into your news reader (Feedly, Inoreader, Apple News, etc.) to get new posts automatically.</span>
                    <xsl:if test="atom:link[@rel='self']/@href">
                        <br/><code><xsl:value-of select="atom:link[@rel='self']/@href"/></code>
                    </xsl:if>
                </div>
            </div>

            <div class="wrap">
                <xsl:choose>
                    <xsl:when test="item">
                        <p class="count"><xsl:value-of select="count(item)"/> recent post<xsl:if test="count(item) != 1">s</xsl:if></p>
                        <xsl:for-each select="item">
                            <div class="item">
                                <div class="meta">
                                    <xsl:if test="category">
                                        <span class="cat"><xsl:value-of select="category"/></span>
                                    </xsl:if>
                                    <span class="date"><xsl:value-of select="substring(pubDate, 1, 16)"/></span>
                                </div>
                                <h2><a href="{link}"><xsl:value-of select="title"/></a></h2>
                                <p><xsl:value-of select="description"/></p>
                                <a class="more" href="{link}">
                                    Read article
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M5 12h13"/></svg>
                                </a>
                            </div>
                        </xsl:for-each>
                    </xsl:when>
                    <xsl:otherwise>
                        <div class="empty">No posts have been published yet. Check back soon.</div>
                    </xsl:otherwise>
                </xsl:choose>
            </div>

            <footer>
                <a href="{link}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Visit the website
                </a>
            </footer>
        </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
