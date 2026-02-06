<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание лицензии</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 1200px;
            margin-top: 30px;
        }
        .generate-btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
        .table-responsive {
            margin-top: 40px;
        }
        .card {
            margin-bottom: 30px;
        }
        .status-active {
            color: #198754;
            font-weight: 500;
        }
        .status-revoked {
            color: #dc3545;
            font-weight: 500;
        }
        .status-expired {
            color: #fd7e14;
            font-weight: 500;
        }
        .badge-status {
            font-size: 0.8em;
            padding: 0.4em 0.8em;
        }
        .btn-revoke {
            transition: all 0.3s ease;
        }
        .btn-revoke:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Форма создания лицензии -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Создание новой лицензии</h4>
        </div>
        <div class="card-body">
            <form action="{{ 'http://localhost:81/licences' }}" method="POST" id="licenceForm">
                @csrf
                <!-- App Key -->
                <div class="mb-3">
                    <label for="app_key" class="form-label">App Key <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('app_key') is-invalid @enderror"
                           id="app_key"
                           name="app_key"
                           value="{{ old('app_key') }}"
                           required>
                    @error('app_key')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Licence Key -->
                <div class="mb-3">
                    <label for="licence_key" class="form-label">Licence Key <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="text"
                               class="form-control @error('licence_key') is-invalid @enderror"
                               id="licence_key"
                               name="licence_key"
                               value="{{ old('licence_key') }}"
                               required>
                        <button type="button" class="btn btn-outline-secondary generate-btn" id="generateKey">
                            Сгенерировать
                        </button>
                    </div>
                    @error('licence_key')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Нажмите "Сгенерировать" для создания сложного ключа</small>
                </div>

                <!-- Expires At -->
                <div class="mb-3">
                    <label for="expires_at" class="form-label">Дата окончания <span class="text-danger">*</span></label>
                    <input type="datetime-local"
                           class="form-control @error('expires_at') is-invalid @enderror"
                           id="expires_at"
                           name="expires_at"
                           value="{{ old('expires_at') }}"
                           required>
                    @error('expires_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Is Revoked -->
                <div class="mb-3">
                    <label for="is_revoked" class="form-label">Статус лицензии <span class="text-danger">*</span></label>
                    <select class="form-select @error('is_revoked') is-invalid @enderror"
                            id="is_revoked"
                            name="is_revoked"
                            required>
                        <option value="">Выберите статус</option>
                        @foreach(\App\Dictionaries\LicenceDictionary::type() as $key => $value)
                            <option value="{{ $key }}" {{ old('is_revoked') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('is_revoked')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">Создать лицензию</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Таблица лицензий -->
    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">Список лицензий</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @if(isset($data) && count($data) > 0)
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">App Key</th>
                            <th scope="col">Licence Key</th>
                            <th scope="col">Дата окончания</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $index => $licence)
                            @php
                                $expiresDate = \Carbon\Carbon::parse($licence->expiresAt);
                                $isExpired = $expiresDate->isPast();

                                // Определение статуса
                                $statusClass = '';
                                $statusText = '';
                                if($licence->isRevoked == \App\Dictionaries\LicenceDictionary::REVOKED) {
                                    $statusClass = 'status-revoked';
                                    $statusText = 'Отозвана';
                                } else {
                                    $statusClass = 'status-active';
                                    $statusText = 'Активна';
                                }
                                if($isExpired) {
                                    $statusClass = 'status-expired';
                                    $statusText = 'Истекла';
                                }

                                // Проверка возможности отзыва
                                $canRevoke = (
                                    $licence->isRevoked != \App\Dictionaries\LicenceDictionary::REVOKED &&
                                    !$isExpired
                                );
                            @endphp
                            <tr>
                                <th scope="row">{{ $index + 1 }}</th>
                                <td>
                                    <code>{{ $licence->appKey }}</code>
                                </td>
                                <td>
                                    <code class="licence-key">{{ $licence->licenceKey }}</code>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-secondary ms-2 copy-btn"
                                            data-key="{{ $licence->licenceKey }}"
                                            title="Копировать ключ">
                                        📋
                                    </button>
                                </td>
                                <td>
                                    <span class="{{ $isExpired ? 'text-danger' : '' }}">
                                        {{ $expiresDate->format('d.m.Y H:i') }}
                                    </span>
                                    @if($isExpired)
                                        <span class="badge bg-warning text-dark badge-status">Истекла</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="{{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                    @if($licence->isRevoked == \App\Dictionaries\LicenceDictionary::REVOKED)
                                        <span class="badge bg-danger badge-status">Отозвана</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($canRevoke)
                                            <form action="{{ 'http://localhost:81/revoke/' . $licence->id }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger btn-revoke"
                                                        onclick="return confirm('Вы уверены, что хотите отозвать эту лицензию?')">
                                                    Отозвать
                                                </button>
                                            </form>
                                        @else
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    disabled
                                                    title="{{ $isExpired ? 'Лицензия истекла' : 'Лицензия уже отозвана' }}">
                                                Отозвать
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info" role="alert">
                        Нет созданных лицензий. Создайте первую лицензию с помощью формы выше.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно для просмотра деталей -->
<div class="modal fade" id="licenceModal" tabindex="-1" aria-labelledby="licenceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="licenceModalLabel">Детали лицензии</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"><strong>App Key:</strong></label>
                    <p id="modalAppKey" class="form-control-plaintext"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Licence Key:</strong></label>
                    <p id="modalLicenceKey" class="form-control-plaintext"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Дата окончания:</strong></label>
                    <p id="modalExpiresAt" class="form-control-plaintext"></p>
                </div>
                <div class="mb-3">
                    <label class="form-label"><strong>Статус:</strong></label>
                    <p id="modalStatus" class="form-control-plaintext"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Генерация сложного ключа
        document.getElementById('generateKey').addEventListener('click', function() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let result = '';
            const segments = 4;
            const segmentLength = 5;

            for (let i = 0; i < segments; i++) {
                for (let j = 0; j < segmentLength; j++) {
                    result += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                if (i < segments - 1) {
                    result += '-';
                }
            }

            document.getElementById('licence_key').value = result;
        });

        // Установка минимальной даты (текущая дата)
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');

        const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        document.getElementById('expires_at').min = minDateTime;

        // Предустановка даты через 1 год от текущей
        const nextYear = new Date(now);
        nextYear.setFullYear(nextYear.getFullYear() + 1);
        const nextYearStr = nextYear.toISOString().slice(0, 16);
        document.getElementById('expires_at').value = nextYearStr;

        // Копирование ключа в буфер обмена
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const key = this.getAttribute('data-key');
                navigator.clipboard.writeText(key).then(() => {
                    const originalText = this.innerHTML;
                    this.innerHTML = '✓';
                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('btn-outline-success');

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-outline-success');
                        this.classList.add('btn-outline-secondary');
                    }, 2000);
                });
            });
        });

        // Модальное окно для просмотра деталей
        const viewButtons = document.querySelectorAll('.view-btn');
        const modalAppKey = document.getElementById('modalAppKey');
        const modalLicenceKey = document.getElementById('modalLicenceKey');
        const modalExpiresAt = document.getElementById('modalExpiresAt');
        const modalStatus = document.getElementById('modalStatus');

        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                modalAppKey.textContent = this.getAttribute('data-appkey');
                modalLicenceKey.textContent = this.getAttribute('data-licencekey');
                modalExpiresAt.textContent = this.getAttribute('data-expiresat');
                modalStatus.textContent = this.getAttribute('data-status');
            });
        });

        // Форматирование отображения licence key (переносы для длинных ключей)
        document.querySelectorAll('.licence-key').forEach(element => {
            const text = element.textContent;
            if (text.length > 20) {
                // Добавляем мягкие переносы после дефисов
                element.innerHTML = text.replace(/-/g, '-<wbr>');
            }
        });

        // Обработка отзыва лицензии
        document.querySelectorAll('.btn-revoke').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Вы уверены, что хотите отозвать эту лицензию?')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
</body>
</html>
