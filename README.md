# GnuBoard Theme for Bootstrap 5

그누보드5 코어를 수정하지 않고 Bootstrap 5를 쓸 수 있게 만든 테마입니다. 게시판·회원·1:1문의·FAQ·쇼핑몰 스킨을 모두 포함하며, 반응형 한 벌로 PC와 모바일을 함께 대응합니다.

## 요구사항

- **그누보드 5.4.0.4 이상** — 상단 메뉴에 쓰는 `get_menu_db()` 가 이 버전에서 추가되었습니다. 5.3.x 에는 `run_replace()`, `run_event()`, `get_pretty_url()`, `get_board_db()`, `get_string_encrypt()`, `get_db_create_replace()` 가 없어 동작하지 않습니다.
- **쇼핑몰 스킨을 함께 쓴다면 그누보드 5.4.6 이상** — 영카트가 그누보드에 통합된 버전부터 `G5_USE_SHOP`, `G5_THEME_SHOP_PATH` 등이 정의됩니다. 커뮤니티만 쓴다면 5.4.0.4 로 충분합니다(쇼핑몰 관련 상수는 모두 `shop/` 안에서만 참조).
- PHP — 5.5.16 + PHP 8.4 조합에서 동작을 확인했습니다. 코드 자체는 PHP 5.4 문법 범위입니다.

## 설치

1. 이 저장소를 그누보드의 `theme/` 아래에 둡니다.

   ```
   cd /path/to/gnuboard/theme
   git clone https://github.com/newkilho/Bootstrap5.git bootstrap5
   ```

   폴더명은 자유입니다. 스킨 경로가 `theme/...` 로 시작해 활성 테마를 따라가므로 이름에 의존하지 않습니다.

2. **관리자 > 환경설정 > 기본환경설정 > 테마** 에서 `bootstrap5` 를 선택합니다.

3. 게시판 스킨은 **게시판 관리**에서 `theme/basic`(기본) 또는 `theme/gallery`(갤러리)로 지정합니다. 회원·검색·최근글 등 나머지 스킨은 테마 선택 시 `theme.config.php` 값에 따라 함께 적용됩니다.

## 사용

### 메인 페이지

테마의 `index.php` 를 고치는 대신 **그누보드 루트에 `main.php` 를 만드세요.** `index.php` 가 이 파일을 자동으로 포함합니다. 테마를 업데이트해도 작업물이 덮어써지지 않습니다.

```
/main.php          ← 메인 콘텐츠
```

### 사이드바

그누보드 루트에 `sidebar.right.php` 를 만들면 메인을 제외한 모든 페이지의 오른쪽에 3컬럼 사이드바가 붙습니다. 파일이 없으면 본문이 전체 폭을 씁니다.

```
/sidebar.right.php ← 오른쪽 사이드바
```

## 설정

`theme.config.php` 에서 바꿉니다.

| 상수 / 키 | 기본값 | 설명 |
|---|---|---|
| `G5_THEME_DEVICE` | `pc` | 반응형이므로 모바일 전용 스킨 없이 PC 스킨 한 벌로 처리 |
| `G5_COMMUNITY_USE` | `true` | `false` 로 두면 쇼핑몰이 초기화면이 되고 게시판 head/tail 도 쇼핑몰 것을 씁니다 |
| `enabled_report` | `true` | 게시물 신고 기능 |
| `enabled_block` | `true` | 회원 차단 기능 |

그 밖에 갤러리 이미지 수·크기, 쇼핑몰 메인 출력 스킨과 상품 수 등을 지정할 수 있고, 게시판 관리의 *테마 설정 가져오기* 로 해당 값을 적용할 수 있습니다.

## 포함 스킨

| 구분 | 스킨 |
|---|---|
| 게시판 | `basic`, `gallery` |
| 회원 | 로그인, 회원가입, 비밀번호 찾기/변경, 프로필, 쪽지, 스크랩, 포인트, 메일폼 |
| 그 외 | 1:1문의, FAQ, 최신글, 전체 최근글, 검색, 접속자, 내용, 로그인박스, 설문조사, 인기검색어, 방문자 |
| 쇼핑몰 | 상품 목록·상세·주문, 장바구니·위시리스트·카테고리 박스, 쿠폰존, 배너 |
| 소셜 | 소셜 로그인 및 회원가입 |

갤러리 스킨의 보기·쓰기·댓글은 `basic` 스킨을 `include` 합니다. 공통 부분은 `skin/board/basic/` 만 고치면 됩니다.

## 신고 / 차단 기능

그누보드에 없는 두 기능을 테마가 자체 제공합니다.

- **게시물 신고** — 게시물 보기와 댓글에 신고 버튼이 붙습니다. 접수되면 `{prefix}board_report` 에 기록하고 관리자 메일로 알립니다. 중복 신고는 막히며 취소는 불가합니다.
- **회원 차단** — 회원 이름 사이드뷰의 *차단하기* 로 등록합니다. 차단한 회원의 글은 목록에서 링크가 사라지고, 직접 열면 목록으로 되돌립니다. 자기 자신과 최고관리자는 차단할 수 없습니다.

두 기능 모두 `api.php` 가 처리합니다. 로그인 여부와 세션 토큰을 검증하고, 필요한 테이블은 첫 사용 시 자동 생성됩니다. 관리 화면은 제공하지 않으므로 신고 내역은 메일 또는 DB에서 확인해야 합니다. 기능을 쓰지 않으려면 `theme.config.php` 의 `enabled_report` / `enabled_block` 을 `false` 로 두면 됩니다.

## 테마 제공 함수

스킨을 직접 만들 때 쓸 수 있는 함수들입니다 (`functions.php`).

```php
// Bootstrap 5 pagination 마크업 생성. get_paging() 과 인자가 같습니다.
get_bs_paging($write_pages, $cur_page, $total_page, $url, $add = '')

// 회원 아이콘·이미지 URL 과 사이드뷰 드롭다운 HTML 반환
get_member_info($mb_id, $name, $email, $homepage, $option = [])
//   $option['css'] 이름에 적용할 클래스, $option['len'] 이름 자르기 길이

// 상단 메뉴(관리자 메뉴 설정 기반) 를 navbar 마크업으로 변환
get_layout_menu($menu)

// 통합검색의 게시판 목록을 버튼 그룹으로 변환
chg_board_list($str_board_list)
```

> **업그레이드 주의** — 이전 버전의 `chg_paging()` 은 제거되었습니다. `get_paging()` 결과를 정규식으로 변환하는 대신 `get_bs_paging()` 이 마크업을 직접 생성합니다. 테마 밖 스킨에서 `chg_paging()` 을 호출하고 있었다면 `get_bs_paging()` 으로 교체해야 합니다. 인자는 `get_paging()` 과 동일합니다.

## 사용 라이브러리

- [Bootstrap 5.3.8](https://getbootstrap.com) — jsDelivr CDN 에서 불러옵니다 (`head.def.php`)
- Font Awesome 4.7 — 그누보드에 포함된 것을 사용합니다
- jQuery 1.12.4 / jQuery Migrate 1.4.1 — 그누보드에 포함된 것을 사용합니다

CDN 을 쓰지 않으려면 `head.def.php` 의 `add_stylesheet()` / `add_javascript()` 경로를 내려받은 파일로 바꾸면 됩니다.

## 라이선스

MIT License — [LICENSE](LICENSE) 참고.
