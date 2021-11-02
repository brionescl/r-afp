const url = localStorage.getItem('url')
const rentabilitiesLast12Months = JSON.parse(localStorage.getItem('rentabilitiesLast12Months'))
const months = JSON.parse(localStorage.getItem('months'))

let selectedFundAdministrator = null
let myChart = null

window.onload = () => {
    const fundAdministrators = document.getElementsByClassName('fund-administrator')
    for (const fundAdministrator of fundAdministrators) {
        fundAdministrator.addEventListener('click', (event) => {
            selectFundAdministrator(event.currentTarget.id)
            updateChart(event.currentTarget.id)
        }, false)
    }
}

const previous = () => {
    const form = document.getElementById('formSelect')
    const previousMonth = parseInt(form.month.value) - 1
    const strPreviousMonth = `${previousMonth}`.length == 1 ? '0' + previousMonth : previousMonth
    const year = parseInt(form.year.value)
    const previousYear = parseInt(form.year.value) - 1
    const minYear = parseInt(form.year[0].value)

    if (previousMonth == 0 && previousYear < minYear) {
        return
    }

    if (previousMonth == 0) {
        window.location.replace(url + `/dashboard/${previousYear}/12`)
        return
    }

    window.location.replace(url + `/dashboard/${year}/${strPreviousMonth}`)
}

const next = () => {
    const form = document.getElementById('formSelect')
    const nextMonth = parseInt(form.month.value) + 1
    const strNextMonth = `${nextMonth}`.length == 1 ? '0' + nextMonth : nextMonth
    const year = parseInt(form.year.value)
    const nextYear = parseInt(form.year.value) + 1
    const maxYear = parseInt(form.year[(form.year.length - 1)].value)

    if (nextMonth == 13 && nextYear > maxYear) {
        return
    }

    if (nextMonth == 13) {
        window.location.replace(url + `/dashboard/${nextYear}/01`)
        return
    }

    window.location.replace(url + `/dashboard/${year}/${strNextMonth}`)
}

const reload = () => {
    const form = document.getElementById('formSelect')
    window.location.replace(url + `/dashboard/${form.year.value}/${form.month.value}`)
}

const sync = () => {
    const form = document.getElementById('formSelect')
    window.location.replace(url + `/dashboard/${form.year.value}/${form.month.value}/sync`)
}

const selectFundAdministrator = (id) => {
    if (selectedFundAdministrator !== null && selectedFundAdministrator != id) {
        document
            .getElementById(selectedFundAdministrator)
            .getElementsByClassName('card')[0]
            .classList
            .remove('selected-card')
    }

    document
        .getElementById(id)
        .getElementsByClassName('card')[0]
        .classList
        .add('selected-card')

    selectedFundAdministrator = id
}

const updateChart = (fundAdministrator) => {
    const form = document.getElementById('formSelect')
    const ctx = document.getElementById('myChart').getContext('2d')

    if (myChart) {
        myChart.destroy()
    }

    myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: buildChartLabels(form.month.value),
            datasets: buildChartDatasets(fundAdministrator, form.month.value, form.year.value)
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    })
}

const buildChartLabels = (selectedMonth) => {
    const labels = []
    let nextMonth = parseInt(selectedMonth) + 1
    let strMonth = ''

    for (let i = 1; i <= 12; i++) {
        if (nextMonth == 13) {
            nextMonth = 1
        }
        strMonth = `${nextMonth}`.length == 1 ? '0' + nextMonth : nextMonth
        labels.push(months[strMonth])
        nextMonth++
    }

    return labels
}

const buildChartDatasets = (fundAdministrator, selectedMonth, selectedYear) => {
    const startDate = moment(`${selectedYear}-${selectedMonth}-01`, 'YYYY-MM-DD')
        .subtract(12, 'month')
        .format('YYYY-MM-DD')
    const investmentFunds = {a: [], b: [], c: [], d: [], e: []}

    for (let i = 1; i <= 12; i++) {
        const date = moment(startDate, 'YYYY-MM-DD')
            .add(i, 'month')
            .format('YYYY-MM-DD')

        investmentFunds.a.push(getRentability(fundAdministrator, 'A', date))
        investmentFunds.b.push(getRentability(fundAdministrator, 'B', date))
        investmentFunds.c.push(getRentability(fundAdministrator, 'C', date))
        investmentFunds.d.push(getRentability(fundAdministrator, 'D', date))
        investmentFunds.e.push(getRentability(fundAdministrator, 'E', date))
    }

    return [{
        label: 'A',
        data: investmentFunds.a,
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
    }, {
        label: 'B',
        data: investmentFunds.b,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
    }, {
        label: 'C',
        data: investmentFunds.c,
        backgroundColor: 'rgba(255, 206, 86, 0.2)',
        borderColor: 'rgba(255, 206, 86, 1)',
        borderWidth: 1
    }, {
        label: 'D',
        data: investmentFunds.d,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }, {
        label: 'E',
        data: investmentFunds.e,
        backgroundColor: 'rgba(153, 102, 255, 0.2)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1
    }]
}

const getRentability = (fundAdministrator, investmentFund, date) => {
    const rentabilities = rentabilitiesLast12Months[fundAdministrator]

    for (const rentability of rentabilities) {
        if (rentability.investment_fund != investmentFund) {
            continue
        }
        if (moment(rentability.date, 'YYYY-MM-DD').isSame(date)) {
            return rentability.rentability
        }
    }

    return null
}
