//all global variables
let tForm = document.querySelector(".t-form");
let allInput = tForm.querySelectorAll("input");
let selectEl = tForm.querySelector("select");
let allBtn = tForm.querySelectorAll("button");
let btnClose = document.querySelector(".btn-close");
let balanceEl = document.querySelector(".balance");
let incomeEl = document.querySelector(".income");
let expenseEl = document.querySelector(".expense");
let tListEl = document.querySelector(".t-list");
let modalBtn = document.querySelector(".modal-btn");
let transaction = [];

if(localStorage.getItem('transaction')!=null){
    transaction = JSON.parse(localStorage.getItem('transaction'))
}
console.log(transaction);

//add transaction code
tForm.onsubmit = (e)=>{
    e.preventDefault();
    let obj = {
        title : allInput[0].value,
        amount : allInput[1].value,
        transaction : selectEl.value,
        date : new Date()
    };
    transaction.push(obj);
    localStorage.setItem('transaction',JSON.stringify(transaction));
    swal("Success",'Transaction Added','success');
    btnClose.click();
    tForm.reset('');
    showTransaction();
    calculation();
}

//format date
const formatDate = (d) =>{
    let date = new Date(d);
    let yy = date.getFullYear();
    let mm = date.getMonth()+1;
    let dd = date.getDate();
    let time = date.toLocaleTimeString();
    mm = mm < 10 ? '0' + mm : mm;
    dd = dd < 10 ? '0' + dd : dd;
    return `${dd}-${mm}-${yy}-${time}`
}

//delete transaction
const deleteFunc = () =>{
    let allDelBtn = tListEl.querySelectorAll(".del-btn");
    allDelBtn.forEach((btn,index)=>{
        btn.onclick = () =>{
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this transaction!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                    transaction.splice(index,1)
                    localStorage.setItem('transaction',JSON.stringify(transaction));
                    showTransaction();
                    calculation();
                    swal("Poof! Your transaction has been deleted!", {
                    icon: "success",
                    });
                } else {
                  swal("Your transaction is safe!");
                }
              });
        }
    })
}

//update transaction
const updateFunc = () =>{
    let allEditBtn =tListEl.querySelectorAll(".edit-btn");
    allEditBtn.forEach((btn,index)=>{
        btn.onclick = () =>{
            modalBtn.click();
            selectEl.value=btn.getAttribute("trans");
            allInput[0].value=btn.getAttribute("title");
            allInput[1].value=btn.getAttribute("amount");
            allBtn[0].classList.add("d-none");
            allBtn[1].classList.remove("d-none");
            allBtn[1].onclick = () =>{
                let obj = {
                    title : allInput[0].value,
                    amount : allInput[1].value,
                    transaction : selectEl.value,
                    date : new Date()
                };
                transaction[index]=obj;
                localStorage.setItem('transaction',JSON.stringify(transaction));
                swal("Success",'Transaction Updated','success');
                btnClose.click();
                tForm.reset('');
                showTransaction();
                calculation();
                allBtn[0].classList.remove("d-none");
                allBtn[1].classList.add("d-none");
            }
        }
    });
}

//show all transactions
const showTransaction = () =>{
    tListEl.innerHTML = "";
    transaction.forEach((item,index)=>{
        tListEl.innerHTML += `
                <tr>
                    <td class="text-nowrap">${item.title}</td>
                    <td class="text-nowrap">₹${item.amount}</td>
                    <td class="text-nowrap">${item.transaction}</td>
                    <td class="text-nowrap">${formatDate(item.date)}</td>
                    <td class="text-nowrap">
                        <button title=${item.title} amount="${item.amount}" trans="${item.transaction}" class="btn edit-btn text-success">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button class="btn del-btn text-danger">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
        `;
    });
    deleteFunc();
    updateFunc();
}
showTransaction();

//calculate transaction
const calculation = () =>{
    let totalIncome = 0;
    let totalExpense = 0;
    let filterIncome = transaction.filter((item)=>item.transaction=='Income');
    for(let obj of filterIncome){
        totalIncome+=Number(obj.amount);
    }
    console.log(totalIncome);
    let filterExpense = transaction.filter((item)=>item.transaction=='Expense');
    filterExpense.forEach((obj)=>{
        totalExpense+=Number(obj.amount);
    });
    incomeEl.innerText = `₹${totalIncome}`;
    expenseEl.innerText = `₹${totalExpense}`;
    Number(totalIncome-totalExpense) < 0 ? balanceEl.style.color = 'red' : balanceEl.style.color = 'green'
    balanceEl.innerText = `₹${Number(totalIncome-totalExpense)}`;
}
calculation();