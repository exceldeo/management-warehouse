<?php
// Prevent direct file access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  header('location: 404.html');
} else {
  // Display menu based on access rights
  $module = $_GET['module'] ?? '';
  $access = $_SESSION['hak_akses'] ?? '';

  $menuItems = [
    [
      'title' => '',
      'access' => [
        'Administrator', 
        'Admin Gudang', 
        'Kepala Gudang'
      ],
      'menu' => [
        'dashboard' => [
          'module' => 'dashboard',
          'active_module' => [
            'dashboard'
          ],
          'icon' => 'fas fa-home', 
          'title' => 'Dashboard', 
          'access' => [
            'Administrator', 
            'Admin Gudang', 
            'Kepala Gudang'
          ], 
        ],
      ]
    ],
    [
      'title' => 'MASTER DATA',
      'access' => [
        'Administrator', 
        'Admin Gudang', 
        
      ],
      'menu' => [
        'barang' => [
          'module' => 'barang',
          'active_module' => [
            'barang',
            'tampil_detail_barang',
            'form_entri_barang',
            'form_ubah_barang',
            'jenis',
            'form_entri_jenis',
            'form_ubah_jenis',
            'satuan',
            'form_entri_satuan',
            'form_ubah_satuan'
          ],
          'icon' => 'fas fa-clone', 
          'title' => 'Barang', 
          'access' => [
            'Administrator', 
            'Admin Gudang', 
            
          ], 
          'sub' => [
            [
              'module' => 'barang',
              'active_module' => [
                'barang',
                'tampil_detail_barang',
                'form_entri_barang',
                'form_ubah_barang'
              ],
              'title' => 'Data Barang',
              'access' => [
                'Administrator', 
                'Admin Gudang', 
                
                ]
            ],
            [
              'module' => 'jenis',
              'active_module' => [
                'jenis',
                'form_entri_jenis',
                'form_ubah_jenis'
              ],
              'title' => 'Jenis Barang',
              'access' => [
                'Administrator', 
                'Admin Gudang', 
                
                ]
            ],
            [
              'module' => 'satuan',
              'active_module' => [
                'satuan',
                'form_entri_satuan',
                'form_ubah_satuan'
              ],
              'title' => 'Satuan',
              'access' => [
                'Administrator', 
                'Admin Gudang', 
                
              ]
            ],
          ]
        ]
      ],
    ],
    [
      'title' => 'TRANSAKSI',
      'access' => [
        'Administrator', 
        'Admin Gudang', 
        
      ],
      'menu' => [
        'barang_masuk' => [
          'module' => 'barang_masuk',
          'active_module' => [
            'barang_masuk',
            'form_entri_barang_masuk',
          ],
          'icon' => 'fas fa-sign-in-alt', 
          'title' => 'Barang Masuk', 
          'access' => [
            'Administrator', 
            'Admin Gudang', 
            
            ]
          ],
        'barang_keluar' => [
          'module' => 'barang_keluar',
          'active_module' => [
            'barang_keluar',
            'form_entri_barang_keluar',
          ],
          'icon' => 'fas fa-sign-out-alt', 
          'title' => 'Barang Keluar', 
          'access' => [
            'Administrator', 
            'Admin Gudang', 
            
            ]
          ],
      ],
    ],
    [
      'title' => 'LAPORAN',
      'access' => [
        'Administrator', 
        'Admin Gudang', 
        'Kepala Gudang'
      ],
      'menu' => [
        'laporan_stok' => [
          'module' => 'laporan_stok',
          'active_module' => [
            'laporan_stok'
          ],
          'icon' => 'fas fa-file-signature', 
          'title' => 'Laporan Stok', 
          'access' => [
            'Administrator', 
            'Admin Gudang', 
            'Kepala Gudang'
            ]
          ],
        'laporan_barang_masuk' => [
          'module' => 'laporan_barang_masuk',
          'active_module' => [
            'laporan_barang_masuk'
          ],
          'icon' => 'fas fa-file-import', 
          'title' => 'Laporan Barang Masuk', 
          'access' => [
            'Administrator', 
            'Admin Gudang', 
            'Kepala Gudang'
            ]
          ],
        'laporan_barang_keluar' => [
          'module' => 'laporan_barang_keluar',
          'active_module' => [
            'laporan_barang_keluar'
          ],
          'icon' => 'fas fa-file-export', 
          'title' => 'Laporan Barang Keluar', 
          'access' => [
            'Administrator', 
            'Admin Gudang', 
            'Kepala Gudang'
            ]
          ],
      ],
    ],
    [
      'title' => 'USER',
      'access' => [
        'Administrator', 
      ],
      'menu' => [
        'user' => [
          'module' => 'user',
          'active_module' => [
            'user',
            'form_entri_user',
            'form_ubah_user'
          ],
          'icon' => 'fas fa-user', 
          'title' => 'Manajemen User', 
          'access' => [
            'Administrator', 
            ]
          ],
      ],
    ]
];
   foreach ($menuItems as $menu) {
      if (in_array($access, $menu['access'])) {
        if ($menu['title'] != ''){
          ?>
            <li class="nav-section">
              <span class="sidebar-mini-icon">
                <i class="fa fa-ellipsis-h"></i>
              </span>
              <h4 class="text-section">
                <?= $menu['title'] ?>
              </h4>
            </li> 
          <?php
        }
        foreach ($menu['menu'] as $key => $value) {
          if (in_array($access, $value['access'])) {
            if (isset($value['sub'])) {
              $is_active = in_array($module, $value['active_module']) ? 'active' : '';
              $is_show = in_array($module, $value['active_module']) ? 'show' : '';
              ?>
                <li class="nav-item <?= $is_active; ?> submenu">
                  <a href="#<?= $value['module'] ?>" data-toggle="collapse" class="nav-link">
                    <i class="<?= $value['icon'] ?>"></i>
                    <p><?= $value['title'] ?></p>
                    <span class="caret"></span>
                  </a>
                  <div class="collapse <?=$is_show?>" id="<?= $value['module'] ?>">
                    <ul class="nav nav-collapse">
                      <?php
                        foreach ($value['sub'] as $sub) {
                          if (in_array($access, $sub['access'])) {
                            $is_active = in_array($module, $sub['active_module']) ? 'active' : '';
                            ?>
                              <li class="<?=$is_active?>">
                                <a href="?module=<?= $sub['module'] ?>">
                                  <span class="sub-item"><?= $sub['title'] ?></span>
                                </a>
                              </li>
                            <?php
                          }
                        }
                      ?>
                    </ul>
                  </div>
                </li>
              <?php
            } else {
              $is_active = in_array($module, $value['active_module']) ? 'active' : '';
              ?>
                <li class="nav-item <?= $is_active; ?>">
                  <a href="?module=<?= $value['module'] ?>" class="nav-link">
                    <i class="<?= $value['icon'] ?>"></i>
                    <p><?= $value['title'] ?></p>
                  </a>
                </li>
              <?php 
            }
          }
        }
      }
    }
  }
?>
