<?php include_once __DIR__ . '/../header-market.php'; ?>

<div class="admin-container">

    <div class="dashboard-header-bar">
        <h2 class="dashboard__heading" style="margin:0;"><?php echo translate('control_panel'); ?></h2>

        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">

            <div class="dashboard-switch-wrapper">
                <input type="checkbox" id="toggleLabels">
                <label for="toggleLabels"><?php echo translate('show_values'); ?></label>
            </div>

            <div id="filterContainer" class="dashboard-filter-container">
                <label for="companyFilter"><?php echo translate('look_at'); ?></label>
                <select id="companyFilter">
                    <option value=""><?php echo translate('super_admin_global_view'); ?></option>
                </select>
            </div>

            <button onclick="window.print()" class="btn-export">
                <i class="fa-solid fa-print"></i> <?php echo translate('report'); ?>
            </button>
        </div>
    </div>

    <div class="admin dashboard-content">

        <div class="dashboard__grid">

            <div class="dashboard__kpis">

                <?php if (isset($roleID) && $roleID == '3'): ?>
                    <div class="kpi-card">
                        <h3><?php echo translate('users'); ?></h3>
                        <p id="kpi-users">...</p>
                    </div>
                <?php endif; ?>

                <div class="kpi-card">
                    <h3><?php echo translate('total_apps'); ?></h3>
                    <p id="kpi-apps">...</p>
                </div>
                <div class="kpi-card">
                    <h3><?php echo translate('historics_ing'); ?></h3>
                    <p id="kpi-revenue">...</p>
                </div>
            </div>

            <div class="dashboard__charts">
                <div class="chart-container full-width">
                    <h3><?php echo translate('income_evolution_30_days'); ?></h3>
                    <canvas id="chartVentasDia"></canvas>
                </div>

                <div class="chart-container half-width">
                    <h3><?php echo translate('top_5_apps'); ?></h3>
                    <canvas id="chartTopApps"></canvas>
                </div>

                <div class="chart-container half-width" id="containerEmpresas">
                    <h3><?php echo translate('income_by_company'); ?></h3>
                    <canvas id="chartEmpresas"></canvas>
                </div>

                <div class="chart-container half-width">
                    <h3><?php echo translate('monthly_income'); ?></h3>
                    <canvas id="chartMensual"></canvas>
                </div>

                <div class="chart-container half-width">
                    <h3><?php echo translate('sales_by_category'); ?></h3>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="chartCategorias"></canvas>
                    </div>
                </div>
            </div>

            <div class="dashboard__table-container">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 1rem;">
                    <h3 class="table-heading" style="margin-bottom: 0; border: none;"><?php echo translate('latest_transactions'); ?></h3>

                    <div class="table-filters" style="display: flex; gap: 10px; align-items: center;">

                        <input type="date" id="dateStart" class="form-control-sm">
                        <span style="color: #666;"><?php echo translate('to'); ?></span>
                        <input type="date" id="dateEnd" class="form-control-sm">

                        <select id="limitFilter" class="form-control-sm">
                            <option value="5"><?php echo translate('last_5'); ?></option>
                            <option value="10" selected><?php echo translate('last_10'); ?></option>
                            <option value="20"><?php echo translate('last_20'); ?></option>
                            <option value="50"><?php echo translate('last_50'); ?></option>
                            <option value="all"><?php echo translate('full_history'); ?></option>
                        </select>

                        <button id="btnFilterTable" class="btn-export" style="background: #0d6efd; color: white; border: none;">
                            <i class="fa-solid fa-filter"></i> <?php echo translate('filter'); ?>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th><?php echo translate('user'); ?></th>
                                <th><?php echo translate('application'); ?></th>
                                <th><?php echo translate('company'); ?></th>
                                <th><?php echo translate('date'); ?></th>
                                <th><?php echo translate('amount'); ?></th>
                                <th><?php echo translate('status'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyTransactions">
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../footer-market.php'; ?>