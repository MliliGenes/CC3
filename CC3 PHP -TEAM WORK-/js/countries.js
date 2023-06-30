const moroccoCities = [];
const endpoint =
  "https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master/countries%2Bcities.json";
fetch(endpoint)
  .then((res) => res.json())
  .then((data) => {
    return data.filter((country) => {
      return country.name == "Morocco";
    });
  })
  .then((mr) => {
    let { cities } = mr[0];
    moroccoCities.push(...cities);
  });

function findMatchs(wordToMatch, arr = moroccoCities) {
  return arr.filter((city) => {
    const regex = new RegExp(wordToMatch, "gi");
    return city.name.match(regex);
  });
}

function moveToInput() {
  searchInput.value = this.textContent;
  list.innerHTML = "";
}

function displayMatchs() {
  const matches = findMatchs(this.value);
  const html = matches
    .map((city) => {
      return `<li class="li">${city.name}</li>`;
    })
    .join("");
  list.innerHTML = html;
  list.querySelectorAll("li").forEach((li) => {
    li.addEventListener("click", moveToInput);
  });
}

const searchInput = document.querySelector("[name='city']");
const list = document.querySelector("ul");
const lis = list.querySelectorAll(".li");

searchInput.addEventListener("change", displayMatchs);
searchInput.addEventListener("keyup", displayMatchs);
