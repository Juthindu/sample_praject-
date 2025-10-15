<script setup>
import AdminLayout from '@/Layouts/Admin/AdminLayout.vue';
import DataTable from '@/Components/Admin/DataTable.vue';
import { Chart, registerables } from 'chart.js';
import { Head } from "@inertiajs/vue3";
import { onMounted, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(...registerables, ChartDataLabels);
const page = usePage();

const consumersCount = page.props.consumersCount;
const thisMonthSamples = page.props.thisMonthSamples;
const thisMonthCompletedResults = page.props.thisMonthCompletedResults;
const thisMonthTotalPaid = page.props.thisMonthTotalPaid;
const monthlySamples = page.props.monthlySamples;
const monthlyOwnSamples = page.props.monthlyOwnSamples;
const sampleStatusCounts = page.props.sampleStatusCounts;
const ownSampleStatusCounts = page.props.ownSampleStatusCounts;
const regions = page.props.regions;
const districts = page.props.districts;
const oics = page.props.oics;
const thisMonthOwnerSamples = page.props.thisMonthOwnerSamples;

// Define product table columns
const consumer_table_columns = [
  { field: 'id', title: 'ID', isUnique: true },
  { field: 'first_name', title: 'First Name' },
  { field: 'last_name', title: 'Last Name' },
  { field: 'nic', title: 'NIC' },
  { field: 'contact_number', title: 'Contact Number' },
  { field: 'address', title: 'Address' },
  { field: 'email', title: 'Email' },
  { field: 'actions', title: 'Actions', cellRenderer: false, width: '50px' },
];

// Reference for the canvas element
const pieChartRef = ref(null)
const barChartRef = ref(null)

const barChartRef2 = ref(null)
const pieChartRef2 = ref(null);


// Mount Chart.js chart
onMounted(() => {
  // Delay chart creation by 1 second (1000ms)
  setTimeout(() => {
    // Pie Chart
    // const pieGradient = {
    //   red: { startColor: '#ADD100', endColor: '#7B920A' },
    //   blue: { startColor: '#8e2de2', endColor: '#4a00e0' },
    //   yellow: { startColor: '#f7971e', endColor: '#ffd200' },
    // };

    // ================= Green Pie Chart: Consumer Sample Status =================
const pieCtx = pieChartRef.value.getContext('2d');

// Define gradients for 4 slices
const pieGradientGreen = {
  green1: { startColor: '#91EFA4', endColor: '#27AE60' },
  green2: { startColor: '#58D68D', endColor: '#1E8449' },
  green3: { startColor: '#82E0AA', endColor: '#229954' },
  green4: { startColor: '#A9DFBF', endColor: '#196F3D' }, // new gradient
};

const gradientGreen1 = pieCtx.createLinearGradient(0, 0, 0, 400);
gradientGreen1.addColorStop(0, pieGradientGreen.green1.startColor);
gradientGreen1.addColorStop(1, pieGradientGreen.green1.endColor);

const gradientGreen2 = pieCtx.createLinearGradient(0, 0, 0, 400);
gradientGreen2.addColorStop(0, pieGradientGreen.green2.startColor);
gradientGreen2.addColorStop(1, pieGradientGreen.green2.endColor);

const gradientGreen3 = pieCtx.createLinearGradient(0, 0, 0, 400);
gradientGreen3.addColorStop(0, pieGradientGreen.green3.startColor);
gradientGreen3.addColorStop(1, pieGradientGreen.green3.endColor);

const gradientGreen4 = pieCtx.createLinearGradient(0, 0, 0, 400);
gradientGreen4.addColorStop(0, pieGradientGreen.green4.startColor);
gradientGreen4.addColorStop(1, pieGradientGreen.green4.endColor);

const pieLabels = Object.keys(sampleStatusCounts);  // Consumer sample statuses
const pieData = Object.values(sampleStatusCounts);

new Chart(pieCtx, {
  type: 'pie',
  data: {
    labels: pieLabels,
    datasets: [{
      label: 'Consumer Sample Status',
      data: pieData,
      backgroundColor: [gradientGreen1, gradientGreen2, gradientGreen3, gradientGreen4],
      borderColor: [gradientGreen1, gradientGreen2, gradientGreen3, gradientGreen4],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'top' },
      title: { display: true, text: 'Consumer Sample Status' },
      datalabels: {
        color: '#fff',
        font: { weight: 'bold', size: 13 },
        formatter: (value, context) => {
          const dataset = context.chart.data.datasets[0];
          const total = dataset.data.reduce((sum, val) => sum + val, 0);
          const percentage = total ? ((value / total) * 100).toFixed(1) : 0;
          return `${value} (${percentage}%)`;
        }
      }
    }
  },
  plugins: [ChartDataLabels]
});


    // ================= Blue Pie Chart: NWSDB Sample Status =================
    const pieCtx2 = pieChartRef2.value.getContext('2d');

    const pieGradientBlue = {
      red: { startColor: '#00b4db', endColor: '#0083b0' },
      blue: { startColor: '#43cea2', endColor: '#185a9d' },
      yellow: { startColor: '#4facfe', endColor: '#00f2fe' },
    };

    const gradientBlue1 = pieCtx2.createLinearGradient(0, 0, 0, 400);
    gradientBlue1.addColorStop(0, pieGradientBlue.red.startColor);
    gradientBlue1.addColorStop(1, pieGradientBlue.red.endColor);

    const gradientBlue2 = pieCtx2.createLinearGradient(0, 0, 0, 400);
    gradientBlue2.addColorStop(0, pieGradientBlue.blue.startColor);
    gradientBlue2.addColorStop(1, pieGradientBlue.blue.endColor);

    const gradientBlue3 = pieCtx2.createLinearGradient(0, 0, 0, 400);
    gradientBlue3.addColorStop(0, pieGradientBlue.yellow.startColor);
    gradientBlue3.addColorStop(1, pieGradientBlue.yellow.endColor);

    const pieLabels2 = Object.keys(ownSampleStatusCounts);  // NWSDB sample statuses
    const pieData2 = Object.values(ownSampleStatusCounts);

    new Chart(pieCtx2, {
      type: 'pie',
      data: {
        labels: pieLabels2,
        datasets: [{
          label: 'NWSDB Sample Status',
          data: pieData2,
          backgroundColor: [gradientBlue1, gradientBlue2, gradientBlue3],
          borderColor: [gradientBlue1, gradientBlue2, gradientBlue3],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' },
          title: { display: true, text: 'NWSDB Sample Status' },
          datalabels: {
            color: '#fff',
            font: { weight: 'bold', size: 13 },
            formatter: (value, context) => {
              const dataset = context.chart.data.datasets[0];
              const total = dataset.data.reduce((sum, val) => sum + val, 0);
              const percentage = total ? ((value / total) * 100).toFixed(1) : 0;
              return `${value} (${percentage}%)`;
            }
          }
        }
      },
      plugins: [ChartDataLabels]
    });



    // Bar Chart
    const barCtx = barChartRef.value.getContext('2d');

    const barCtx2 = barChartRef2.value.getContext('2d');

    const months = Object.keys(monthlySamples).map(month =>
      new Date(2025, month - 1).toLocaleString('default', { month: 'short' })
    );
    const barData = Object.values(monthlySamples);

    const months2 = Object.keys(monthlyOwnSamples).map(month =>
      new Date(2025, month - 1).toLocaleString('default', { month: 'short' })
    );
    const barData2 = Object.values(monthlyOwnSamples);

    // const colorGradient = { startColor: '#00b09b', endColor: '#96c93d' };
    const colorGradient = { startColor: '#00b09b', endColor: '#96c93d' };

    const gradientGreen = barCtx.createLinearGradient(0, 0, 0, 400);
    gradientGreen.addColorStop(0, colorGradient.startColor);
    gradientGreen.addColorStop(1, colorGradient.endColor);


    new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: months.length > 0 ? months : ['No Data'],
        datasets: [{
          label: 'Samples per Month',
          data: barData.length > 0 ? barData : [0],
          // backgroundColor: "#7B920A",
          backgroundColor: "#91EFA4",

          // borderColor: gradientGreen,
          borderWidth: 1,
        }]
      },
      options: {
        responsive: true,
        animation: {
          duration: 1000,
          easing: 'easeOutQuart'
        },
        scales: {
          y: { beginAtZero: true }
        },
        plugins: {
          legend: { display: true },
          title: { display: true, text: 'Consumer Samples per Month (This Year)' },

          datalabels: {
            color: '#000',
            anchor: 'end',
            align: 'top',
            font: {
              weight: 'bold',
              size: 13
            },
            formatter: (value) => value,
          }
        }
      },
      plugins: [ChartDataLabels]
    });

    new Chart(barCtx2, {
      type: 'bar',
      data: {
        labels: months2.length > 0 ? months2 : ['No Data'],
        datasets: [{
          label: 'Samples per Month',
          data: barData2.length > 0 ? barData2 : [0],
          // backgroundColor: "#7B920A",
          backgroundColor: "#91EAE4",

          // borderColor: gradientGreen,
          borderWidth: 1,
        }]
      },
      options: {
        responsive: true,
        animation: {
          duration: 1000,
          easing: 'easeOutQuart'
        },
        scales: {
          y: { beginAtZero: true }
        },
        plugins: {
          legend: { display: true },
          title: { display: true, text: 'NWSDB Samples per Month (This Year)' },

          // ✅ Add this block to show actual values above bars
          datalabels: {
            color: '#000',
            anchor: 'end',   // position relative to bar
            align: 'top',    // place label above bar
            font: {
              weight: 'bold',
              size: 13
            },
            formatter: (value) => value,  // just show the number
          }
        }
      },
      plugins: [ChartDataLabels]
    });

  }, 500)  // 1000 milliseconds = 1 second delay
});
</script>

<template>
  <AdminLayout>

    <Head title="Dashboard" />

    <!-- Dashboard Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">DASHBOARD</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Dashboard Content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">

          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #00b4db, #0083b0); color: #fff;">
              <div class="inner">
                <h3>{{ regions }}</h3>
                <p>Total Regions</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-location-outline"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #43cea2, #185a9d); color: #fff;">
              <div class="inner">
                <h3>{{ districts }}<sup style="font-size: 20px"></sup></h3>
                <p>Total Districts</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-location-outline"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #28A745, #218838); color: #fff;">
              <div class="inner">
                <h3>{{ consumersCount }}</h3>
                <p>Total Consumers</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #2ECC71, #27AE60); color: #fff;">
              <div class="inner">
                <h3>{{ thisMonthSamples }}<sup style="font-size: 20px"></sup></h3>
                <p>This Month’s Consumer Samples</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #4facfe, #00f2fe); color: #fff;">
              <div class="inner">
                <h3>{{ oics }}</h3>
                <p>Total OICs</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-location-outline"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #1e3c72, #2a5298); color: #fff;">
              <div class="inner">
                <h3>{{ thisMonthOwnerSamples }} </h3>
                <p>This Month’s NWSDB Samples</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #58D68D, #2ECC71); color: #fff;">
              <div class="inner">
                <h3>{{ thisMonthCompletedResults }}</h3>
                <p>Completed Results (This Month)</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #82E0AA, #27AE60); color: #fff;">
              <div class="inner">
                <h3>{{ thisMonthTotalPaid.toLocaleString() }} </h3>
                <p>Total Paid (This Month)</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

        </div>

        <!-- Chart Row -->
        <div class="row mt-4">
          <div class="col-6">
            <div class="card card-default">
              <div class="card-body">
                <canvas ref="barChartRef2"></canvas>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card card-default">
              <div class="card-body">
                <canvas ref="barChartRef"></canvas>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card card-default">
              <div class="card-body d-flex justify-content-center">
                <canvas ref="pieChartRef2"></canvas>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="card card-default">
              <div class="card-body d-flex justify-content-center">
                <canvas ref="pieChartRef"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- DataTable Row -->
        <div class="row mt-4">
          <div class="col-12">
            <div class="card card-default">
              <div class="card-body" style="padding: 0px;">
                <DataTable title="Consumer TABLE" fetch_url="/api/consumers" :columns="consumer_table_columns"
                  table_icon='<i class="nav-icon fas fa-archive" style="font-size: medium;"></i>' modal_title="Consumer"
                  edit_route_name='new.consumer.edit' delete_route_name='new.consumer.delete' view_button=false />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </AdminLayout>
</template>

<style scoped>
canvas {
  height: 400px !important;
}

/* .order-color{
    background: linear-gradient(to right, #8e2de2, #4a00e0) !important;
}
.visitor-color{
    background: linear-gradient(45deg, #C04848, #480048) !important;
}
.register-color{
    background: linear-gradient(to right, #f7971e, #ffd200) !important;
}
.rate-color{
    background: linear-gradient(45deg, #00b09b, #96c93d) !important;
} */
</style>
