import fetch from "node-fetch";
import { JSDOM } from "jsdom";

const res = await fetch("https://www.realitycechy.cz/clanky");
const html = await res.text();

const dom = new JSDOM(html);
const document = dom.window.document;

const items = [...document.querySelectorAll("article")].slice(0, 3);

const data = items.map(item => ({
    title: item.querySelector("h2 a")?.textContent.trim(),
    link: item.querySelector("h2 a")?.href
}));

console.log(data);
