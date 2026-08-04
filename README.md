# Order & Campaign API - Case Study

Bu proje, bir e-ticaret senaryosu için sipariş yönetimini ve esnek kampanya değerlendirme kurallarını uygulayan bir REST API uygulamasıdır.

## 🛠️ Kullanılan Teknolojiler
- **PHP 8.4**
- **Laravel 11+**
- **MySQL** (Laragon entegre veritabanı sunucusu)
- **Composer**

---

## 💻 Laragon ile Kurulum ve Çalıştırma

Projeyi yerel bilgisayarınızda Laragon kullanarak ayağa kaldırmak için aşağıdaki adımları takip edebilirsiniz:

### 1. Dosya Düzeni
Projeyi Laragon'un kök dizini olan `C:\laragon\www` klasörünün altına taşıyın (Örnek: `C:\laragon\www\OrderCampaignAPI`).

### 2. Veritabanı Oluşturma
Laragon üzerinde MySQL servisini başlatın. Ardından HeidiSQL, phpMyAdmin veya favori veritabanı yönetim aracınızla yerel sunucunuzda aşağıdaki isimde boş bir veritabanı oluşturun:
* **Veritabanı Adı:** `order_campaign_api`

### 3. Çevre Değişkenleri (.env) Kurulumu
Proje ana dizinindeki `.env` dosyasını açıp veritabanı bağlantı bilgilerini ve API anahtarını kontrol edin. Laragon için varsayılan ayarlar şu şekilde olmalıdır:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=order_campaign_api
DB_USERNAME=root
DB_PASSWORD=

# API Güvenliği için anahtar tanımı:
API_KEY=SiparisCampaignApi2026!
```

### 4. Bağımlılıkların Kurulumu ve Veritabanı Yapılandırması
Laragon üzerinden **Terminal** uygulamasını açın ve proje dizinine giderek sırasıyla şu komutları çalıştırın:

```bash
# 1. Composer paketlerini yükleyin
composer install

# 2. Uygulama anahtarını oluşturun
php artisan key:generate

# 3. Tabloları oluşturun ve JSON dosyalarından (authors, categories, products) verileri içeri aktarın (Seed)
php artisan migrate --seed
```

### 5. Uygulamayı Çalıştırma
* Laragon'un "Auto Virtual Hosts" özelliği aktif ise projeye tarayıcınızdan **`http://OrderCampaignAPI.test`** adresinden erişebilirsiniz.
* Alternatif olarak, Laragon terminalinde aşağıdaki Laravel yerel sunucu komutunu çalıştırarak **`http://localhost:8000`** adresini kullanabilirsiniz:
  ```bash
  php artisan serve
  ```

---

## 🧪 Testlerin Çalıştırılması

Yazılan entegrasyon ve birim testlerini çalıştırmak için terminalde şu komutu çalıştırabilirsiniz:
```bash
php artisan test
```

---

## 📡 REST API Kullanım Kılavuzu

Uygulamadaki tüm API istekleri **`X-API-KEY`** başlığı ile kimlik doğrulamasına tabidir. İstek atarken header kısmına `.env` dosyasında tanımlı olan API anahtarı eklenmelidir.

### 🔑 Authentication Header:
* **Key:** `X-API-KEY`
* **Value:** `SiparisCampaignApi2026!`

---

### 📥 1. Sipariş Oluşturma (Create Order)
Sipariş oluşturmak için sepet içeriğini POST isteği olarak gönderin. Stok kontrolü otomatik yapılır ve sepetiniz için en avantajlı tek bir kampanya seçilerek uygulanır.

* **URL:** `/api/orders`
* **Method:** `POST`
* **Headers:**
  ```http
  Accept: application/json
  Content-Type: application/json
  X-API-KEY: SiparisCampaignApi2026!
  ```
* **Request Body (JSON):**
  ```json
  {
      "items": [
          {
              "product_id": 1,
              "quantity": 2
          },
          {
              "product_id": 3,
              "quantity": 1
          }
      ]
  }
  ```
* **Başarılı Yanıt Örneği (201 Created):**
  ```json
  {
      "message": "Sipariş başarıyla oluşturuldu",
      "order": {
          "order_id": 1,
          "user_id": 1,
          "sub_total": 106.6,
          "discount_amount": 14.62,
          "shipping_cost": 0,
          "campaign_name": "%15 Roman Kategorisi İndirimi",
          "total_amount": 91.98,
          "order_status": "pending",
          "applied_campaigns": [
              "%15 Roman Kategorisi İndirimi"
          ],
          "updated_at": "2026-07-30T12:00:00.000000Z",
          "created_at": "2026-07-30T12:00:00.000000Z"
      }
  }
  ```

---

### 📤 2. Sipariş Detayı Görüntüleme (Show Order)
Sipariş numarasına ait finansal dökümü ve ürünlerin detaylı bilgilerini görüntüler.

* **URL:** `/api/orders/{order_id}`
* **Method:** `GET`
* **Headers:**
  ```http
  Accept: application/json
  X-API-KEY: SiparisCampaignApi2026!
  ```
* **Başarılı Yanıt Örneği (200 OK):**
  ```json
  {
      "order_id": 1,
      "user_id": 1,
      "order_status": "pending",
      "created_at": "2026-07-30T12:00:00.000000Z",
      "sub_total": "106.60",
      "campaign_name": "%15 Roman Kategorisi İndirimi",
      "discount_amount": "14.62",
      "shipping_cost": "0.00",
      "total_amount": "91.98",
      "order_items": [
          {
              "product_id": 1,
              "product_name": "İnce Memed",
              "quantity": 2,
              "price": "48.75",
              "total_price": 97.5
          },
          {
              "product_id": 3,
              "product_name": "Kürk Mantolu Madonna",
              "quantity": 1,
              "price": "9.10",
              "total_price": 9.1
          }
      ]
  }
  ```