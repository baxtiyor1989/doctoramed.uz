#!/bin/bash
set -euo pipefail

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOG_FILE="$PROJECT_DIR/storage/logs/deploy.log"
VERSION_FILE="$PROJECT_DIR/public/version.txt"
LOCK_FILE="$PROJECT_DIR/storage/framework/deploy.lock"
STATUS_FILE="$PROJECT_DIR/storage/framework/deploy-status.json"
REPOSITORY_URL="${DEPLOY_GIT_REPOSITORY:-https://github.com/baxtiyor1989/doctoramed.uz.git}"
REMOTE="${DEPLOY_GIT_REMOTE:-origin}"
BRANCH="${DEPLOY_GIT_BRANCH:-master}"

mkdir -p "$(dirname "$LOG_FILE")" "$(dirname "$LOCK_FILE")"
exec > >(tee -a "$LOG_FILE") 2>&1

log() {
  echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

write_status() {
  local status="$1"
  local message="$2"
  local version="${3:-}"
  local tmp_file="${STATUS_FILE}.tmp"

  printf '{"status":"%s","message":"%s","version":"%s","updated_at":"%s"}\n' \
    "$status" "$message" "$version" "$(date '+%Y-%m-%d %H:%M:%S')" > "$tmp_file"
  mv "$tmp_file" "$STATUS_FILE"
}

exec 9>"$LOCK_FILE"
if ! flock -n 9; then
  log "Oldingi deploy hali tugamagan. Yangi deploy bekor qilindi."
  exit 0
fi

trap 'exit_code=$?; failed_line=$LINENO; log "Deploy xatolik bilan to‘xtadi (qator: $failed_line)."; write_status "failed" "Deploy $failed_line-qatorda xatolik bilan to‘xtadi"; exit $exit_code' ERR

write_status "running" "Deploy jarayoni davom etmoqda"
log "============================================"
log "Doctor A Med loyihasini yangilash boshlandi"
log "Joylashuv: $PROJECT_DIR"
log "============================================"

cd "$PROJECT_DIR"

if [ ! -d "$PROJECT_DIR/.git" ]; then
  log ".git topilmadi. Repository birinchi marta ulanmoqda..."
  git init
fi

log "GitHub repository manzili tekshirilmoqda..."
if git remote get-url "$REMOTE" >/dev/null 2>&1; then
  git remote set-url "$REMOTE" "$REPOSITORY_URL"
else
  git remote add "$REMOTE" "$REPOSITORY_URL"
fi

log "GitHub repozitoriy $REMOTE/$BRANCH branchidan yangilanmoqda..."
git fetch "$REMOTE" "$BRANCH"
git reset --hard "$REMOTE/$BRANCH"

log "Composer kutubxonalari o‘rnatilmoqda..."
export COMPOSER_HOME="${COMPOSER_HOME:-/tmp/doctoramed-composer}"
mkdir -p "$COMPOSER_HOME"
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

log "Frontend kutubxonalari va production build tayyorlanmoqda..."
npm ci --no-audit --no-fund
npm run build

log "Ma'lumotlar bazasi migratsiyalari bajarilmoqda..."
php artisan migrate --force

log "Laravel keshlari yangilanmoqda..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

log "Storage link tekshirilmoqda..."
if [ ! -L "$PROJECT_DIR/public/storage" ]; then
  php artisan storage:link
fi

COMMIT_COUNT="$(git rev-list HEAD --count)"
COMMIT_HASH="$(git rev-parse --short HEAD)"
DEPLOYED_AT="$(date '+%Y-%m-%d %H:%M:%S %z')"
VERSION="1.0.${COMMIT_COUNT}"

TMP_VERSION_FILE="${VERSION_FILE}.tmp"
{
  echo "Tizim versiyasi: ${VERSION}"
  echo "Commit: ${COMMIT_HASH}"
  echo "Deployed at: ${DEPLOYED_AT}"
} > "$TMP_VERSION_FILE"
mv "$TMP_VERSION_FILE" "$VERSION_FILE"

log "Versiya yozildi: ${VERSION} (${COMMIT_HASH})"
log "============================================"
log "Deploy muvaffaqiyatli yakunlandi"
log "Tizim versiyasi: $VERSION"
log "============================================"
write_status "success" "Deploy muvaffaqiyatli yakunlandi" "$VERSION ($COMMIT_HASH)"
