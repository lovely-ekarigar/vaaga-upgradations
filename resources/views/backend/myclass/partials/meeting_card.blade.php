<div class="col-md-6">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
            <div class="d-flex align-items-center">
                <span class="badge {{ ($meeting['running'] ?? 'false') == 'true' ? 'bg-success' : 'bg-secondary' }} me-2"></span>
                <h6 class="mb-0 text-truncate" style="max-width: 200px" title="{{ $meeting['meetingName'] ?? 'Meeting' }}">
                    {{ $meeting['meetingName'] ?? 'Class' }}
                </h6>
            </div>
            <small class="text-muted">
                <a href="https://manager.bigbluemeeting.com/lb?meeting={{ $meeting['internalMeetingID'] ?? $meeting['meetingID'] }}&lang=en" target="_blank">
                    <i class="nav-icon icon-feed"></i>
                </a>
                <a class="ms-2 joinDemo" style="margin-left:10px;" href="javascript:void(0)" data-mid="{{ $meeting['meetingID'] }}">
                    <i class="fas fa-play"></i>
                </a>
            </small>
        </div>

        <div class="card-body p-0">
            <div class="d-flex border-bottom">
                <div class="p-2 flex-grow-1 border-end">
                    <small class="text-muted d-block">Started</small>
                    @php
                        $startTimestamp = isset($meeting['startTime']) && is_numeric($meeting['startTime']) ? intval($meeting['startTime']) / 1000 : null;
                        $startTimeFormatted = $startTimestamp ? date('d M h:i A', $startTimestamp) : 'N/A';
                    @endphp
                    <small class="fw-semibold">{{ $startTimeFormatted }}</small>
                </div>
                <div class="p-2 text-center" style="width: 95px">
                    <small class="text-muted d-block">Participants</small>
                    <small class="fw-semibold">{{ $meeting['participantCount'] ?? 0 }}</small>
                </div>
                <div class="p-2 text-center" style="width: 95px">
                    <small class="text-muted d-block">Moderators</small>
                    <small class="fw-semibold">{{ $meeting['moderatorCount'] ?? 0 }}</small>
                </div>
            </div>

            @php
                $attendees = $meeting['attendees']['attendee'] ?? [];
                if (isset($attendees['fullName'])) {
                    $attendees = [$attendees];
                }
            @endphp

            @if(!empty($attendees))
            <div class="table-responsive">
                <table class="table table-sm table-borderless mb-0">
                    <thead>
                        <tr class="text-muted border-bottom">
                            <th class="fw-normal py-1 ps-3" style="width: 30%">Name</th>
                            <th class="fw-normal py-1 text-center" style="width: 15%">Role</th>
                            <th class="fw-normal py-1 text-center" style="width: 15%">Device</th>
                            <th class="fw-normal py-1 text-center" style="width: 15%"><i class="fas fa-presentation"></i></th>
                            <th class="fw-normal py-1 text-center" style="width: 15%"><i class="fas fa-microphone"></i></th>
                            <th class="fw-normal py-1 pe-3 text-center" style="width: 10%"><i class="fas fa-video"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendees as $attendee)
                        <tr class="border-bottom">
                            <td class="ps-3 py-1">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-light text-dark rounded-circle me-2" style="width: 20px; height: 20px; line-height: 20px; font-size: 0.6rem">
                                        {{ substr(($attendee['fullName'] ?? '-'), 0, 1) }}
                                    </span>
                                    <small class="text-truncate" style="max-width: 120px" title="{{ $attendee['fullName'] ?? '-' }}">{{ $attendee['fullName'] ?? '-' }}</small>
                                </div>
                            </td>
                            <td class="text-center py-1">
                                <small class="badge {{ strtolower($attendee['role'] ?? '') === 'moderator' ? 'bg-primary text-white' : 'bg-light text-dark' }}">
                                    {{ ucfirst(strtolower($attendee['role'] ?? '-')) }}
                                </small>
                            </td>
                            <td class="text-center py-1">
                                <small class="text-muted">{{ $attendee['clientType'] ?? '-' }}</small>
                            </td>
                            <td class="text-center py-1">
                                <i class="fas fa-xs {{ ($attendee['isPresenter'] ?? 'false') === 'true' ? 'fa-check text-success' : 'fa-times text-secondary' }}"></i>
                            </td>
                            <td class="text-center py-1">
                                <i class="fas fa-xs {{ ($attendee['hasJoinedVoice'] ?? 'false') === 'true' ? 'fa-microphone text-success' : 'fa-microphone-slash text-secondary' }}"></i>
                            </td>
                            <td class="pe-3 text-center py-1">
                                <i class="fas fa-xs {{ ($attendee['hasVideo'] ?? 'false') === 'true' ? 'fa-video text-success' : 'fa-video-slash text-secondary' }}"></i>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-3">
                <small class="text-muted"><i class="fas fa-user-slash me-1"></i> No attendees</small>
            </div>
            @endif
        </div>
    </div>
</div>