.PHONY: help start logs logs-err logs-access install db-setup test clean commit commit-feat commit-fix commit-chore commit-style commit-docs commit-test commit-refactor commit-perf commit-push commit-pull commit-status push pull status

PHP := php
PORT := 8000
HOST := localhost

help:
	@echo "📋 Comandos disponíveis:"
	@echo ""
	@echo "  make start           🚀 Iniciar servidor PHP"
	@echo "  make logs           📜 Ver logs do PHP"
	@echo "  make logs-err      ❌ Ver apenas erros"
	@echo "  make logs-access   🌐 Ver logs de acesso"
	@echo "  make install       📦 Instalar dependências"
	@echo "  make db-setup      🗄️  Configurar banco"
	@echo "  make clean         🧹 Limpar cache"
	@echo ""
	@echo "  Git:"
	@echo "  make commit-feat    ✨ Nova funcionalidade"
	@echo "  make commit-fix     🔧 Correção de bug"
	@echo "  make commit-style  💄 Estilo (CSS, JS)"
	@echo "  make commit-refactor ♻️ Refatoração"
	@echo "  make commit-perf   ⚡ Performance"
	@echo "  make commit-chore  📦 Manutenção"
	@echo "  make commit-docs   📝 Documentação"
	@echo "  make commit-test  🧪 Testes"
	@echo ""
	@echo "  make push         ⬆️  Enviar para remoto"
	@echo "  make pull         ⬇️  Baixar do remoto"
	@echo "  make status      📊 Status do git"

start:
	@echo "🚀 Iniciando servidor em http://$(HOST):$(PORT)"
	$(PHP) -S $(HOST):$(PORT) -t .

logs:
	@if [ -f php://error.log ]; then tail -f php://error.log; \
	else echo "📭 Nenhum log encontrado"; fi

logs-err:
	@if [ -f php://error.log ]; then tail -50 php://error.log | grep -i error; \
	else echo "📭 Nenhum log de erros encontrado"; fi

logs-access:
	@if [ -f access.log ]; then tail -f access.log; \
	else echo "📭 Nenhum log de acesso encontrado"; fi

install:
	@if [ -f composer.json ]; then composer install; \
	else echo "📦 Nenhum composer.json encontrado"; fi

db-setup:
	@if [ -f database/schema.sql ]; then \
		mysql -u root -p < database/schema.sql; \
		echo "✅ Banco configurado"; \
	else echo "⚠️  Schema não encontrado"; fi

test:
	@echo "🧪 Executando testes..."
	@if [ -f phpunit.xml ]; then vendor/bin/phpunit; \
	else echo "⚠️  PHPUnit não configurado"; fi

clean:
	@echo "🧹 Limpando cache..."
	@rm -rf vendor/ node_modules/ *.log
	@find . -type d -name "cache" -exec rm -rf {} + 2>/dev/null || true
	@find . -type f -name "*.cache" -delete 2>/dev/null || true
	@echo "✅ Limpeza concluída"

commit-feat:
ifndef msg
	$(error msg é obrigatório. Use: make commit-feat msg='nova funcionalidade')
endif
	@git add -A
	@git commit -m "✨ feat: $(msg)"
	@echo "✅ Commit: ✨ feat: $(msg)"

commit-fix:
ifndef msg
	$(error msg é obrigatório. Use: make commit-fix msg='correção')
endif
	@git add -A
	@git commit -m "🔧 fix: $(msg)"
	@echo "✅ Commit: 🔧 fix: $(msg)"

commit-style:
ifndef msg
	$(error msg é obrigatório. Use: make commit-style msg='ajuste')
endif
	@git add -A
	@git commit -m "💄 style: $(msg)"
	@echo "✅ Commit: 💄 style: $(msg)"

commit-refactor:
ifndef msg
	$(error msg é obrigatório. Use: make commit-refactor msg='refatoração')
endif
	@git add -A
	@git commit -m "♻️ refactor: $(msg)"
	@echo "✅ Commit: ♻️ refactor: $(msg)"

commit-perf:
ifndef msg
	$(error msg é obrigatório. Use: make commit-perf msg='otimização')
endif
	@git add -A
	@git commit -m "⚡ perf: $(msg)"
	@echo "✅ Commit: ⚡ perf: $(msg)"

commit-chore:
ifndef msg
	$(error msg é obrigatório. Use: make commit-chore msg='tarefa')
endif
	@git add -A
	@git commit -m "📦 chore: $(msg)"
	@echo "✅ Commit: 📦 chore: $(msg)"

commit-docs:
ifndef msg
	$(error msg é obrigatório. Use: make commit-docs msg='documentação')
endif
	@git add -A
	@git commit -m "📝 docs: $(msg)"
	@echo "✅ Commit: 📝 docs: $(msg)"

commit-test:
ifndef msg
	$(error msg é obrigatório. Use: make commit-test msg='teste')
endif
	@git add -A
	@git commit -m "🧪 test: $(msg)"
	@echo "✅ Commit: 🧪 test: $(msg)"

commit-push: push

commit-pull: pull

commit-status: status

push:
	@git push
	@echo "⬆️  Enviado para remoto"

pull:
	@git pull
	@echo "⬇️  Atualizado do remoto"

status:
	@git status