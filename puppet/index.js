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

// const agent = new useragent().toString();
let mobile = false;
const agent = new useragent({ deviceCategory: 'mobile'}).toString();
// $const agent = new useragent({ deviceCategory: 'desktop'}).toString();
const before = new Date().getTime();
let el = '.ytp-large-play-button';

(async() => {
  const browser = await puppeteer.launch();
  // const browser = await puppeteer.launch({executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome'});
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
  await page.waitFor(2000);

  await page.waitForSelector(el);
  await page.click(el);
  await page.waitFor(12000);

  await page.screenshot({path: `example-${new Date().getTime()}.png`});

  await browser.close();

  console.log(`executed in ${((new Date().getTime() - before) / 1000)} seconds`);
})();
