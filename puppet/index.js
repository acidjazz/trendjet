/*
 * index.js
 * Copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 *
 * Distributed under terms of the APACHE license.
 */
'use strict'
const fs = require('fs');
const puppeteer = require('puppeteer');
const useragent = require('user-agents');

const agent = new useragent({ deviceCategory: 'desktop'}).toString();
const before = new Date().getTime();

let mobile = false;
let el = '.ytp-large-play-button';

(async() => {

  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  page.setUserAgent(agent);
  await page.goto('https://www.youtube.com/watch?v=-LcV8duImEw');

  if (page._target._targetInfo.url.includes('https://m.youtube')) {
    mobile = true
  }

  if (mobile) {
    el = '.html5-main-video'
  }

  // fs.writeFileSync('body.html', await page.evaluate(() => document.body.innerHTML));
  await page.waitFor(1000);
  await page.waitForSelector(el);
  await page.click(el);
  await page.waitFor(2000);
  await page.screenshot({type: 'jpeg', quality: 1, path: `example-${new Date().getTime()}.jpg`});
  await browser.close();

  console.log(`executed in ${((new Date().getTime() - before) / 1000)} seconds`);

})();
