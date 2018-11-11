'use strict'

require('dotenv').config()

const fs = require('fs');
const puppeteer = require('puppeteer');
const useragent = require('user-agents');
const axios = require('axios');
const exec = require('child_process').exec;

const before = new Date().getTime();

let mobile = false;
let el = '.ytp-large-play-button';
let video_ids = process.argv[2].split(',');
let boost_ids = process.argv[3].split(',');
let index = 0;

(async() => {

  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  console.log(`[PUPPET]: video_ids: ${process.argv[2]} boost_ids: ${process.argv[3]}`)

  for (let id of video_ids) {

    let agent = new useragent({ deviceCategory: 'desktop'}).toString();
    page.setUserAgent(agent);
    await page.goto(`https://www.youtube.com/watch?v=${id}`);

    if (page._target._targetInfo.url.includes('https://m.youtube')) {
      mobile = true
    }

    if (mobile) {
      el = '.html5-main-video'
    }

    await page.waitFor(1000);
    await page.waitForSelector(el);
    await page.click(el);
    await page.waitFor(6000);
    let file = `shot:${id}:${boost_ids[index]}:${new Date().getTime()}.jpg`;
    await page.screenshot({type: 'jpeg', quality: 1, path: file});
    exec(`aws s3 cp ${file} s3://trendjet-shots/ --grants read=uri=http://acs.amazonaws.com/groups/global/AllUsers`);

    console.log(`[PUPPET] ${process.env.APP_URL}boost/${boost_ids[index]}/?apikey=${process.env.API_KEY}`);
    try {
      await axios.put(`${process.env.APP_URL}boost/${boost_ids[index]}/?apikey=${process.env.API_KEY}`);
    } catch (error) {
      console.log(`[PUPPET:ERROR] ${process.env.APP_URL}boost/${boost_ids[index]}/?apikey=${process.env.API_KEY}`);
      console.log(error.message);
      throw error;
    }

    console.log(`[PUPPET] ${process.env.APP_URL}shot/?apikey=${process.env.API_KEY}`, {file:file});
    try {
      await axios.post(`${process.env.APP_URL}shot/?apikey=${process.env.API_KEY}`, {file:file});
    } catch (error) {
      console.log(`[PUPPET:ERROR] ${process.env.APP_URL}shot/?apikey=${process.env.API_KEY}`, {file:file});
      console.log(error.message);
      throw error;
    }

    index++;

  }

  await browser.close();

  console.log(`executed in ${((new Date().getTime() - before) / 1000)} seconds`);

})();
