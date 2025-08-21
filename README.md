# Sistem Surat Cuti - Dinas Kesehatan

A comprehensive leave application system for health department employees with flexible approval workflows.

## 🚀 Features

### Core Features
- **Multi-role User Management** (ASN, PPPK, Admin, etc.)
- **Flexible Disposisi Workflow** - Process approvals in any order
- **Digital Signature Integration** - Upload and manage digital signatures
- **PDF Generation** - Generate official leave certificates
- **Leave Balance Tracking** - Automatic calculation and tracking
- **Comprehensive Reporting** - Analytics and export capabilities

### Advanced Features
- **Conditional Approval Logic** - Sekretariat workflow with OR logic for Umpeg/Perencanaan Keu
- **Cross-unit Role Support** - Roles that can handle multiple organizational units
- **Real-time Status Tracking** - Live updates on approval progress
- **History Management** - Complete audit trail for all transactions

## 🏗️ Architecture

### Clean Code Principles
- **Single Responsibility** - Each class has one clear purpose
- **Dependency Injection** - Proper IoC container usage
- **Repository Pattern** - Clean data access layer
- **Service Layer** - Business logic separation
- **SOLID Principles** - Maintainable and extensible code

### Key Components

#### Controllers
- `DisposisiController` - Handles approval workflow
- `SuratCutiController` - Manages leave applications
- `QuickLoginController` - Development authentication helper

#### Models
- `SuratCuti` - Leave application entity
- `DisposisiCuti` - Approval step entity
- `AlurCuti` - Workflow definition entity
- `User` - User management with roles

#### Services
- Conditional approval logic for Sekretariat
- PDF generation with digital signatures
- Leave balance calculation
- Notification system

## 🔄 Workflow System

### Organizational Units

#### Sekretariat (NEW)
```
Karyawan → Kasubag → (Umpeg OR Perencanaan Keu) → Sekdin → KADIN
```
**Special Logic**: If either Umpeg or Perencanaan Keu approves, the other is automatically approved.

#### Puskesmas
```
Karyawan → KTU → Kapus → Umpeg → Sekdin → KADIN
```

#### Bidang
```
Karyawan → Kabid → Umpeg → Sekdin → KADIN
```

### Flexible Processing
- **No Sequential Order Required** - Any approver can process their step anytime
- **Parallel Processing** - Multiple approvers can work simultaneously
- **Conditional Logic** - Smart auto-approval for specific scenarios
- **Real-time Updates** - Instant status synchronization

## 🛠️ Installation

### Requirements
- PHP 8.1+
- Laravel 10.x
- MySQL 8.0+
- Composer
- Node.js & NPM

### Setup Steps

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd surat-cuti
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Storage Setup**
   ```bash
   php artisan storage:link
   ```

6. **Optimization**
   ```bash
   php artisan optimize
   ```

## 🧪 Development & Testing

### Quick Login System
For development environment, access `/quick-login` for instant authentication with test accounts.

#### Available Test Accounts

**Sekretariat Workflow:**
- `karyawan-sekretariat` - Employee
- `kasubag-sekretariat` - Department Head
- `kasubag-umpeg` - HR Department (Conditional)
- `kasubag-perencanaan` - Planning & Finance (Conditional)
- `sekretaris-dinas` - Secretary
- `kepala-dinas` - Department Director

**Other Roles:**
- `admin` - System Administrator
- `asn` / `pppk` - General Employees
- `katu` / `kapus` - Puskesmas Staff

All test accounts use password: `password`

### Testing Scenarios

1. **Basic Workflow**
   - Login as employee → Create leave application
   - Login as approvers → Process in any order
   - Verify final approval and PDF generation

2. **Conditional Logic**
   - Create Sekretariat leave application
   - Approve as Umpeg → Verify Perencanaan Keu auto-approval
   - Complete remaining approvals

3. **History Tracking**
   - Check disposisi history for all users
   - Verify complete audit trail
   - Test PPPK-specific features

## 📁 Project Structure

```
app/
├── Http/Controllers/
│   ├── DisposisiController.php      # Approval workflow
│   ├── SuratCutiController.php      # Leave management
│   ├── Admin/                       # Admin panel
│   └── Debug/                       # Development tools
├── Models/
│   ├── SuratCuti.php               # Leave application
│   ├── DisposisiCuti.php           # Approval steps
│   ├── AlurCuti.php                # Workflow definition
│   └── User.php                    # User management
└── Services/                       # Business logic

resources/
├── views/
│   ├── disposisi/                  # Approval views
│   ├── surat-cuti/                 # Leave application views
│   ├── debug/                      # Development views
│   └── layouts/                    # Shared layouts

database/
├── migrations/                     # Database schema
└── seeders/                       # Test data
```

## 🔧 Configuration

### Environment Variables
```env
APP_NAME="Sistem Surat Cuti"
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=surat_cuti
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
# Configure mail settings for notifications
```

### Key Features Configuration

#### Workflow Customization
Edit `database/seeders/AlurCutiSeeder.php` to modify approval workflows.

#### Role Management
Configure user roles and permissions in `app/Models/User.php`.

#### PDF Templates
Customize PDF layouts in `resources/views/surat-cuti/pdf.blade.php`.

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Set up SSL certificates
- [ ] Configure mail server
- [ ] Set up backup strategy
- [ ] Configure monitoring

### Optimization Commands
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 📊 Monitoring & Maintenance

### Log Files
- Application logs: `storage/logs/laravel.log`
- Web server logs: Check your web server configuration

### Database Maintenance
- Regular backups recommended
- Monitor table sizes and performance
- Clean up old disposisi records periodically

### Performance Optimization
- Use Redis for caching in production
- Optimize database queries
- Implement queue system for heavy operations

## 🤝 Contributing

### Code Standards
- Follow PSR-12 coding standards
- Use meaningful variable and method names
- Write comprehensive docblocks
- Maintain test coverage

### Development Workflow
1. Create feature branch
2. Implement changes with tests
3. Run code quality checks
4. Submit pull request
5. Code review and merge

## 📝 License

This project is proprietary software developed for Dinas Kesehatan.

## 🆘 Support

For technical support or questions:
- Check the documentation
- Review existing issues
- Contact the development team

---

**Version**: 2.0.0  
**Last Updated**: August 2025  
**Developed by**: Development Team