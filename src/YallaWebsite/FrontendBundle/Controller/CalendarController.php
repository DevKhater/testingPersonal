<?php namespace YallaWebsite\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CalendarController extends Controller
{

    public function loadAction(Request $request)
    {
        return $this->render('YallaWebsiteFrontendBundle:Events:index.html.twig');
    }

    public function indexAction(Request $request)
    {
        // Get current year, month and day
        list($iNowYear, $iNowMonth, $iNowDay) = explode('-', date('Y-m-d'));

        // Get current year and month depending on possible GET parameters
        if ($request->get('month')) {
            list($iMonth, $iYear) = explode('-', $request->get('month'));
            $iMonth = (int) $iMonth;
            $iYear = (int) $iYear;
            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository('YallaWebsiteBackendBundle:Event')->getEventDaysinMonthYear($iMonth, $iYear);
        } else {
            list($iMonth, $iYear) = explode('-', date('n-Y'));
            $iMonth = (int) $iMonth;
            $iYear = (int) $iYear;
            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository('YallaWebsiteBackendBundle:Event')->getEventDaysinMonthYear($iMonth, $iYear);
        }

        // Get name and number of days of specified month
        $iTimestamp = mktime(0, 0, 0, $iMonth, $iNowDay, $iYear);
        list($sMonthName, $iDaysInMonth) = explode('-', date('F-t', $iTimestamp));

        // Get previous year and month
        $iPrevYear = $iYear;
        $cPrevYear = $iYear-1;
        $cNextYear = $iYear+1;
        $iPrevMonth = $iMonth - 1;
        if ($iPrevMonth <= 0) {
            $iPrevYear--;
            $iPrevMonth = 12; // set to December
        }

        // Get next year and month
        $iNextYear = $iYear;
        $iNextMonth = $iMonth + 1;
        if ($iNextMonth > 12) {
            $iNextYear++;
            $iNextMonth = 1;
        }

        // Get number of days of previous month
        $iPrevDaysInMonth = (int) date('t', mktime(0, 0, 0, $iPrevMonth, $iNowDay, $iPrevYear));

        // Get numeric representation of the day of the week of the first day of specified (current) month
        $iFirstDayDow = (int) date('w', mktime(0, 0, 0, $iMonth, 1, $iYear));

        // On what day the previous month begins
        $iPrevShowFrom = $iPrevDaysInMonth - $iFirstDayDow + 1;

        // If previous month
        $bPreviousMonth = ($iFirstDayDow > 0);

        // Initial day
        $iCurrentDay = ($bPreviousMonth) ? $iPrevShowFrom : 1;

        $bNextMonth = false;
        $sCalTblRows = '';
        $table_header = '<table id="calendar_table"><thead><tr><th class="weekday">sun</th><th class="weekday">mon</th><th class="weekday">tue</th><th class="weekday">wed</th><th class="weekday">thu</th><th class="weekday">fri</th><th class="weekday">sat</th></tr></thead><tbody>';
        // Generate rows for the calendar
        for ($i = 0; $i < 6; $i++) { // 6-weeks range
            $sCalTblRows .= '<tr>';
            for ($j = 0; $j < 7; $j++) { // 7 days a week
                $sClass = '';
                if ($iNowYear == $iYear && $iNowMonth == $iMonth && $iNowDay == $iCurrentDay && !$bPreviousMonth && !$bNextMonth) {
                    $sClass = 'today';
                } elseif (!($bPreviousMonth) && !($bNextMonth)) {
                    $sClass = 'current';
                }

                if (array_key_exists($iCurrentDay, $entities)) {
                    //$thisDate = str_replace(' ', '', $iYear).'-'.str_replace(' ', '', $iMonth).'-'.str_replace(' ', '', $iCurrentDay);
                    $ajaxFunc = 'javascript:getEvents("';
                    $ajaxFunc .= "$iYear-$iMonth-$iCurrentDay";
                    $ajaxFunc .= '");';
                    $sClass .= " eventAvailable";

                    $sCalTblRows .= '<td class="' . $sClass . '"><span onclick=' . $ajaxFunc . '>'
                        . $iCurrentDay . '</td>';
                } else {
                    $sCalTblRows .= '<td class="' . $sClass . '"><span>' . $iCurrentDay . '</span></td>';
                }

                // Next day
                $iCurrentDay++;
                if ($bPreviousMonth && $iCurrentDay > $iPrevDaysInMonth) {
                    $bPreviousMonth = false;
                    $iCurrentDay = 1;
                }
                if (!$bPreviousMonth && !$bNextMonth && $iCurrentDay > $iDaysInMonth) {
                    $bNextMonth = true;
                    $iCurrentDay = 1;
                }
            }
            $sCalTblRows .= '</tr>';
        }
        // Prepare replacement keys and generate the calendar
        $sCalTblRows = $table_header . $sCalTblRows . "</tbody></table>";
        $aKeys = array(
            '__prev_month__' => "{$iPrevMonth}-{$iPrevYear}",
            '__next_month__' => "{$iNextMonth}-{$iNextYear}",
            '__prev_year__' => "{$iMonth}-{$cPrevYear}",
            '__next_year__' => "{$iMonth}-{$cNextYear}",
            '__cal_caption_year__' => $iYear,
            '__cal_caption_month__' => $sMonthName
        );
        $nav = $this->render('YallaWebsiteFrontendBundle:Template:calendar_navigation.html.twig', $aKeys)->getContent();
        $json = json_encode(array("nav" => $nav, "cal" => $sCalTblRows));
        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
