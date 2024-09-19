const addUser = document.getElementById("add-user");
const indexOuter = document.getElementById("index-outer");
const userList = document.getElementById("user-list");



addUser.onclick = () => {
  userList.style.display = "flex"; 
  console.log("Add User clicked");
};

indexOuter.onclick = (event) => {
  if (event.target !== addUser && !addUser.contains(event.target)) {
    userList.style.display = "none"; 
  }
};